<?php

# Можно было запилить модуль для проекта и вынести все обработчики в него, тогда код был бы по-чище

AddEventHandler(
    '',
    'onAfterTwigTemplateEngineInited',
    function (\Twig_Environment $engine) {

        $engine->addExtension(new Kint_TwigExtension());

        return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, array($engine));
    }
);
