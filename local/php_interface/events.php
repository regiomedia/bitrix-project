<?php

AddEventHandler(
    '',
    'onAfterTwigTemplateEngineInited',
    function (\Twig_Environment $engine) {

        $engine->addExtension(new Kint_TwigExtension());

        return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, array($engine));
    }
);
