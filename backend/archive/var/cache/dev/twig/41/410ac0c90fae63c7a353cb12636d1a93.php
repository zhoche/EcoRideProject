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
class __TwigTemplate_a008e33873c8b1b00140b9bbd93bf1af extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "emails/ride_feedback.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "emails/ride_feedback.html.twig"));

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
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["passenger"]) || array_key_exists("passenger", $context) ? $context["passenger"] : (function () { throw new RuntimeError('Variable "passenger" does not exist.', 8, $this->source); })()), "pseudo", [], "any", false, false, false, 8), "html", null, true);
        yield ",</p>

    <p>
      Le trajet EcoRide avec <strong>";
        // line 11
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["ride"]) || array_key_exists("ride", $context) ? $context["ride"] : (function () { throw new RuntimeError('Variable "ride" does not exist.', 11, $this->source); })()), "driver", [], "any", false, false, false, 11), "pseudo", [], "any", false, false, false, 11), "html", null, true);
        yield "</strong> est maintenant terminé.
    </p>

    <p>Merci de confirmer votre expérience :</p>

    <p>
      <a href=\"";
        // line 17
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["link"]) || array_key_exists("link", $context) ? $context["link"] : (function () { throw new RuntimeError('Variable "link" does not exist.', 17, $this->source); })()), "html", null, true);
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
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

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
        return array (  72 => 17,  63 => 11,  57 => 8,  48 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"fr\">
  <head>
    <meta charset=\"UTF-8\" />
    <title>Confirmation de trajet - EcoRide</title>
  </head>
  <body style=\"font-family: Arial, sans-serif; line-height: 1.6; color: #333;\">
    <p>Bonjour {{ passenger.pseudo }},</p>

    <p>
      Le trajet EcoRide avec <strong>{{ ride.driver.pseudo }}</strong> est maintenant terminé.
    </p>

    <p>Merci de confirmer votre expérience :</p>

    <p>
      <a href=\"{{ link }}\"
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
", "emails/ride_feedback.html.twig", "/Users/zoecinetique/Desktop/EcoRide/ecorideproject/backend/templates/emails/ride_feedback.html.twig");
    }
}
