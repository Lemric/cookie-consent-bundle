<?php

namespace huppys\CookieConsentBundle\tests\Form;

use huppys\CookieConsentBundle\Entity\ConsentCategory;
use huppys\CookieConsentBundle\Entity\ConsentCookie;
use huppys\CookieConsentBundle\Entity\ConsentDetailedConfiguration;
use huppys\CookieConsentBundle\Enum\FormSubmitName;
use huppys\CookieConsentBundle\Form\ConsentCategoryType;
use huppys\CookieConsentBundle\Form\ConsentCookieType;
use huppys\CookieConsentBundle\Form\ConsentDetailedType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConsentDetailedTypeTest extends TypeTestCase
{
    #[Test]
    #[DataProvider('submittedFormProvider')]
    public function shouldHaveClickedButton($formData, $expectedClickedButton): void
    {
        $formModel = new ConsentDetailedConfiguration();

        $formModel->setDescription($formData['description']);

        foreach ($formData['categories'] as $category) {

            $consentCategory = new ConsentCategory();
            $consentCategory->setName($category['name']);

            foreach ($category['cookies'] as $cookie) {
                $consentCookie = new ConsentCookie();

                // explicitly set fields from formData
                $consentCookie->setName($cookie['name']);
                $consentCookie->setValue($cookie['value']);
                $consentCookie->setDescriptionKey($cookie['descriptionKey']);

                $consentCategory->getCookies()->add($consentCookie);
            }

            $formModel->getCategories()->add($consentCategory);
        }

        $form = $this->factory->create(ConsentDetailedType::class, $formModel);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($form->getClickedButton()->getName(), $expectedClickedButton);
    }

    /**
     * @throws Exception
     */
    protected function getExtensions(): array
    {
        $translatorInterfaceMock = $this->createMock(TranslatorInterface::class);

        $consentDetailedType = new ConsentDetailedType($translatorInterfaceMock);
        $consentCategoryType = new ConsentCategoryType($translatorInterfaceMock);
        $consentCookieType = new ConsentCookieType($translatorInterfaceMock);

        return [
            new PreloadedExtension([$consentDetailedType, $consentCategoryType, $consentCookieType], []),
        ];
    }

    public static function submittedFormProvider(): array
    {
        return [
            'dataset:accept_all_clicked' => [
                [
                    FormSubmitName::ACCEPT_ALL => true,
                    'description' => 'test_detailed_type',
                    'categories' => [
                        [
                            'name' => 'analytics',
                            'cookies' => [
                                [
                                    'name' => 'googleanalytics',
                                    'value' => true,
                                    'descriptionKey' => 'googleanalytics',
                                ]
                            ],
                        ],
                        [
                            'name' => 'tracking',
                            'cookies' => [
                                [
                                    'name' => 'googletagmanager',
                                    'value' => true,
                                    'descriptionKey' => 'googletagmanager',
                                ]
                            ],
                        ],
                        [
                            'name' => 'social_media',
                            'cookies' => [
                                [
                                    'name' => 'meta',
                                    'value' => false,
                                    'descriptionKey' => 'meta',
                                ],
                                [
                                    'name' => 'google',
                                    'value' => true,
                                    'descriptionKey' => 'google',
                                ]
                            ],
                        ],
                    ],
                    'consent_version' => 1,
                ],
                FormSubmitName::ACCEPT_ALL,
            ]
        ];
    }
}
