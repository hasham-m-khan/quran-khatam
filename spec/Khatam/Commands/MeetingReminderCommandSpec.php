<?php

namespace spec\Khatam\Commands;

use \Khatam\Commands\MeetingReminderCommand;
use \Khatam\Repositories\KhatamRepository;
use \PHPMailer\PHPMailer\PHPMailer;
use \PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class MeetingReminderCommandSpec extends ObjectBehavior
{
    public function let(KhatamRepository $repo, PHPMailer $mailer)
    {
        $this->beConstructedWith($repo, $mailer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MeetingReminderCommand::class);
    }

    public function it_doesnt_send_emails_for_nonexistant_khatam(
        KhatamRepository $repo, 
        PHPMailer $mailer
    ) {
        $repo->getCurrentKhatam()->willReturn(null);
        $mailer->send()->shouldNotBeCalled();
        $this->run();
    }

    public function it_doesnt_send_emails_for_empty_list(
        KhatamRepository $repo,
        PHPMailer $mailer
    ) {
        $khatam = json_decode(json_encode([
            'id' => 1,
            'meetingLink' => ''
        ]));
        $repo->getCurrentKhatam()->willReturn($khatam);
        $repo->getKhatamUserList(1)->willReturn([]);


        $mailer->addAddress(Argument::any())->willReturn(null);
        $mailer->isHTML()->willReturn(null);
        $mailer->send()->shouldNotBeCalled();
        $this->run();
    }

    public function it_doesnt_email_if_khatam_end_is_greater_than_2_days_from_today (
        KhatamRepository $repo,
        PHPMailer $mailer
    ) {
        $currentKhatam = $repo->getCurrentKhatam();
        $khatam = json_decode(json_encode([
            'id' => 1,
            'endDate' => date($currentKhatam->endDate, strtotime("-3 days"))
        ]));

        // $currentKhatam->endDate->should
    }

}
