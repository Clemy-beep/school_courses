<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private $pages = [
        'users' => [
            'title' => "Users",
            'url' => '/user/'
        ],
        'lessons' => [
            'title' => "Lessons",
            'url' => '/lesson/'
        ],
        'grades' => [
            'title' => "Grades",
            'url' => '/grade/'
        ],
        'exams' => [
            'title' => "Exams",
            'url' => '/exam/'
        ],
        'logout' => [
            'title' => "Log out",
            'url' => '/logout'
        ]
    ];
    public function setUserAvatar(Admin $user, mixed $avatarFile, SluggerInterface $slugger)
    {
        if ($avatarFile) {
            $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFilename);
            $newFileName = $safeFileName . '-' . uniqid() . '.' . $avatarFile->guessExtension();

            try {
                $avatarFile->move(
                    $this->getParameter('avatar_directory'),
                    $newFileName
                );
            } catch (FileException $e) {
                var_dump($e);
            }
            $user->setAvatar($newFileName);
        }
    }

    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdminRepository $adminRepository, SluggerInterface $slugger): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();
            $this->setUserAvatar($admin, $avatarFile, $slugger);
            $adminRepository->add($admin);
            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/new.html.twig', [
            'admin' => $admin,
            'form' => $form,
            "pages" => $this->pages
        ]);
    }

    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(Admin $admin): Response
    {
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
            "pages" => $this->pages
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Admin $admin, AdminRepository $adminRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();
            $this->setUserAvatar($admin, $avatarFile, $slugger);
            $adminRepository->add($admin);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Admin $admin, AdminRepository $adminRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $admin->getId(), $request->request->get('_token'))) {
            $adminRepository->remove($admin);
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
