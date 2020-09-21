<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\UserBundle\Tests\Mailer;

use FOS\UserBundle\Mailer\TwigSymfonyMailer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\NullTransport;
use Symfony\Component\Mime\Exception\RfcComplianceException;

class TwigSymfonyMailerTest extends TestCase
{
    /**
     * @dataProvider goodEmailProvider
     */
    public function testSendConfirmationEmailMessageWithGoodEmails($emailAddress)
    {
        $mailer = $this->getTwigSymfonyMailer();
        $mailer->sendConfirmationEmailMessage($this->getUser($emailAddress));

        $this->assertTrue(true);
    }

    /**
     * @dataProvider badEmailProvider
     */
    public function testSendConfirmationEmailMessageWithBadEmails($emailAddress)
    {
        $this->expectException(RfcComplianceException::class);

        $mailer = $this->getTwigSymfonyMailer();
        $mailer->sendConfirmationEmailMessage($this->getUser($emailAddress));
    }

    /**
     * @dataProvider goodEmailProvider
     */
    public function testSendResettingEmailMessageWithGoodEmails($emailAddress)
    {
        $mailer = $this->getTwigSymfonyMailer();
        $mailer->sendResettingEmailMessage($this->getUser($emailAddress));

        $this->assertTrue(true);
    }

    /**
     * @dataProvider badEmailProvider
     */
    public function testSendResettingEmailMessageWithBadEmails($emailAddress)
    {
        $this->expectException(RfcComplianceException::class);

        $mailer = $this->getTwigSymfonyMailer();
        $mailer->sendResettingEmailMessage($this->getUser($emailAddress));
    }

    public function goodEmailProvider()
    {
        return [
            ['foo@example.com'],
            ['foo@example.co.uk'],
            [$this->getEmailAddressValueObject('foo@example.com')],
            [$this->getEmailAddressValueObject('foo@example.co.uk')],
        ];
    }

    public function badEmailProvider()
    {
        return [
            ['foo'],
            [$this->getEmailAddressValueObject('foo')],
        ];
    }

    private function getTwigSymfonyMailer()
    {
        return new TwigSymfonyMailer(
            new Mailer(
                new NullTransport()
            ),
            $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')->getMock(),
            $this->getTwigEnvironment(),
            [
                'template'   => [
                    'confirmation' => 'foo',
                    'resetting'    => 'foo',
                ],
                'from_email' => [
                    'confirmation' => ['foo@example.com' => 'Foo Example'],
                    'resetting'    => 'foo@example.com',
                ],
            ]
        );
    }

    private function getTwigEnvironment()
    {
        return new \Twig\Environment(new \Twig\Loader\ArrayLoader([
            'foo' => <<<'TWIG'
{% block subject 'foo' %}

{% block body_text %}Test{% endblock %}

TWIG
    ,
        ]));
    }

    private function getUser($emailAddress)
    {
        $user = $this->getMockBuilder('FOS\UserBundle\Model\UserInterface')->getMock();
        $user->method('getEmail')
            ->willReturn($emailAddress);

        return $user;
    }

    private function getEmailAddressValueObject($emailAddressAsString)
    {
        $emailAddress = $this->getMockBuilder('EmailAddress')
            ->setMethods(['__toString'])
            ->getMock();

        $emailAddress->method('__toString')
            ->willReturn($emailAddressAsString);

        return $emailAddress;
    }
}
