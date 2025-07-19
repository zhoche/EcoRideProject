<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* emails/ride_feedback.html.twig */
class __TwigTemplate_39642caaa5e2aeb6f2eba7169ea04d48 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html lang=\"fr\">
  <head>
    <meta charset=\"UTF-8\" />
    <title>Confirmation de trajet - EcoRide</title>
  </head>
  <body style=\"font-family: Arial, sans-serif; line-height: 1.6; color: #333;\">
    <p>Bonjour ";
        // line 8
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["passenger"] ?? null), "pseudo", [], "any", false, false, false, 8), "html", null, true);
        yield ",</p>

    <p>
      Le trajet EcoRide avec <strong>";
        // line 11
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["ride"] ?? null), "driver", [], "any", false, false, false, 11), "pseudo", [], "any", false, false, false, 11), "html", null, true);
        yield "</strong> est maintenant terminé.
    </p>

    <p>Merci de confirmer votre expérience :</p>

    <p>
      <a href=\"";
        // line 17
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["link"] ?? null), "html", null, true);
        yield "\"
         style=\"display: inline-block; padding: 10px 20px; background-color: #6fb586; color: white; text-decoration: none; border-radius: 5px;\">
        Laisser un avis
      </a>
    </p>

    <p>
      Merci pour votre confiance,<br />
      — L’équipe <strong>EcoRide</strong>
    </p>
  </body>
</html>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "emails/ride_feedback.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  66 => 17,  57 => 11,  51 => 8,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "emails/ride_feedback.html.twig", "/Users/zoecinetique/Desktop/EcoRide/ecorideproject/backend/templates/emails/ride_feedback.html.twig");
    }
}
