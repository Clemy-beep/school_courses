<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {

        $this->encoder = $encoder;
    }
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

    public function getUserRoles(User $user)
    {
        if ($user->getMentor() !== null && !in_array('ROLE_ADMIN', $user->getRoles())) {
            $user->setRoles(['ROLE_MENTOR', 'ROLE_USER']);
        }
    }

    public function setUserAvatar(User $user, mixed $avatarFile, SluggerInterface $slugger)
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

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        foreach ($users as $key => $user) {
            $this->getUserRoles($user);
        }
        return $this->render('user/index.html.twig', [
            'users' => $users,
            "pages" => $this->pages
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashpwd = $this->encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($hashpwd);
            $avatarFile = $form->get('avatar')->getData();
            $this->setUserAvatar($user, $avatarFile, $slugger);
            $userRepository->add($user);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            "pages" => $this->pages
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $this->getUserRoles($user);
        return $this->render('user/show.html.twig', [
            'user' => $user,
            "pages" => $this->pages
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();
            $this->setUserAvatar($user, $avatarFile, $slugger);
            $userRepository->add($user);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            "pages" => $this->pages
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mentor', name: 'app_mentor_index', methods: ['GET'])]
    public function mentorIndex(UserRepository $userRepository): Response
    {
        $mentors = $userRepository->findAll();
        foreach ($mentors as $key => $mentor) {
            $this->getUserRoles($mentor);
            if (!in_array('ROLE_MENTOR', $mentor->getRoles())) {
                unset($mentors[$key]);
            }
        }
        return $this->render('user/mentor.html.twig', [
            'mentors' => $mentors,
            'pages' => $this->pages
        ]);
    }
}
