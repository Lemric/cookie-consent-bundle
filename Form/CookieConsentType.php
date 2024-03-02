<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\Form;

use huppys\CookieConsentBundle\Cookie\CookieChecker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CookieConsentType extends AbstractType
{
    protected CookieChecker $cookieChecker;
    protected array $cookieCategories;
    protected bool $csrfProtection;
    protected TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator,
        CookieChecker $cookieChecker,
        array         $cookieCategories,
        bool          $csrfProtection = true
    )
    {
        $this->cookieChecker = $cookieChecker;
        $this->cookieCategories = $cookieCategories;
        $this->csrfProtection = $csrfProtection;
        $this->translator = $translator;
    }

    /**
     * Build the cookie consent form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->cookieCategories as $category) {
            $categoryTitle = $this->translate('cookie_consent.' . $category . '.title');
            $categoryDescription = $this->translate('cookie_consent.' . $category . '.description');
            
            $builder->add($category, ChoiceType::class, [
                'label' => $categoryTitle,
                'expanded' => true,
                'multiple' => false,
                'data' => $this->cookieChecker->isCategoryAllowedByUser($category) ? 'true' : 'false',
                'choices' => [
                    'cookie_consent.yes' => 'true',
                    'cookie_consent.no' => 'false',
                ],
                'choice_attr' => function ($choice, string $key, mixed $value) {
                    return ['class' => 'js-cookie-consent-form-element'];
                },
                'help' => $categoryDescription,
            ]);
        }

        $builder->add('save', SubmitType::class, [
            'label' => $this->translate('cookie_consent.save'),
            'attr' => [
                'class' => 'cookie-consent__btn js-submit-cookie-consent-form'
            ]
        ]);
        $builder->add('reject_all', SubmitType::class, [
            'label' => $this->translate('cookie_consent.reject_all'),
            'attr' => [
                'class' => 'cookie-consent__btn js-reject-all-cookies'
            ]
        ]);
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'CookieConsentBundle',
            'csrf_protection' => $this->csrfProtection,
            'csrf_token_id' => 'csrf_cookie_consent',
        ]);
    }

    protected function translate(string $key): string
    {
        return $this->translator->trans($key, [], 'CookieConsentBundle');
    }
}
