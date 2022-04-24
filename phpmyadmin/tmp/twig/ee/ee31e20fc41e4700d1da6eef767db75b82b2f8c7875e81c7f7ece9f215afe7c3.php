<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* database/designer/edit_delete_pages.twig */
class __TwigTemplate_ae2a3d229230ae4cc5db012f11579148c30868000f2ae57f04c9958863e40826 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<form action=\"";
        echo PhpMyAdmin\Url::getFromRoute("/database/designer");
        echo "\" method=\"post\" name=\"edit_delete_pages\" id=\"edit_delete_pages\" class=\"ajax\">
    ";
        // line 2
        echo PhpMyAdmin\Url::getHiddenInputs(($context["db"] ?? null));
        echo "
    <fieldset id=\"page_edit_delete_options\">
        <input type=\"hidden\" name=\"operation\" value=\"";
        // line 4
        echo twig_escape_filter($this->env, ($context["operation"] ?? null), "html", null, true);
        echo "\">
        <label for=\"selected_page\">
            ";
        // line 6
        echo twig_escape_filter($this->env, (((($context["operation"] ?? null) == "editPage")) ? (_gettext("Page to open")) : (_gettext("Page to delete"))), "html", null, true);
        echo ":
        </label>
        ";
        // line 8
        $this->loadTemplate("database/designer/page_selector.twig", "database/designer/edit_delete_pages.twig", 8)->display(twig_to_array(["pdfwork" =>         // line 9
($context["pdfwork"] ?? null), "pages" =>         // line 10
($context["pages"] ?? null)]));
        // line 12
        echo "    </fieldset>
</form>
";
    }

    public function getTemplateName()
    {
        return "database/designer/edit_delete_pages.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  61 => 12,  59 => 10,  58 => 9,  57 => 8,  52 => 6,  47 => 4,  42 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/designer/edit_delete_pages.twig", "/home/dev/www/phpmyadmin/templates/database/designer/edit_delete_pages.twig");
    }
}
