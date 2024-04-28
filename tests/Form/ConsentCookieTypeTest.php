<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\tests\Form;

use huppys\CookieConsentBundle\Form\ConsentCookieType;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConsentCookieTypeTest extends TypeTestCase
{
    /**
     * Test submit of ConsentCookieType.
     */
    public function testSubmitValidDate(): void
    {
        $formData = [
            'analytics'    => 'true',
            'tracking'     => 'true',
            'marketing'    => 'false',
        ];

        $form = $this->factory->create(ConsentCookieType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($formData, $form->getData());
    }

    /**
     * @throws Exception
     */
    protected function getExtensions(): array
    {
        $translatorInterfaceMock = $this->createMock(TranslatorInterface::class);

        $type = new ConsentCookieType($translatorInterfaceMock);

        return [
            new PreloadedExtension([$type], []),
        ];
    }
}
