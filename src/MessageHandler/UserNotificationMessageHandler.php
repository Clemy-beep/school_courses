<?php

namespace App\MessageHandler;

use App\Helpers\MessageHelper;
use App\Message\UserNotificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use UserNotifierService;
use App\Entity\User;

final class UserNotificationMessageHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var UserNotifierService
     */
    private MessageHelper $messageService;

    public function __construct(EntityManagerInterface $em, MessageHelper $messageService)
    {
        $this->em = $em;
        $this->messageService = $messageService;
    }
    public function __invoke(UserNotificationMessage $message)
    {
        $user = $this->em->find(User::class, $message->getUserId());
        if ($user)
            $this->messageService->sendMessage($user);
    }
}
