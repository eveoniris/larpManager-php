<?php

/* groupe/personnage_add.twig */
class __TwigTemplate_47fccd81b2beb105b7c4cb98fecb1aa2ecbca93cdb802ccc6d9393d1192ba7a1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "groupe/personnage_add.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "nom", array()), "html", null, true);
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
<div class=\"well bs-component\">\t
\t<form action=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.personnage.add", array("index" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()))), "html", null, true);
        echo "\" method=\"POST\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo ">
\t\t<fieldset>
\t\t\t<legend>";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "nom", array()), "html", null, true);
        echo " - <small>Création d'un personnage</small></legend>
\t\t\t";
        // line 11
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "Form/bootstrap_3_layout.html.twig"));
        // line 12
        echo "
\t\t\t";
        // line 13
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "nom", array()), 'row');
        echo "
\t\t\t";
        // line 14
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "surnom", array()), 'row');
        echo "
\t\t\t";
        // line 15
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "age", array()), 'row');
        echo "
\t\t\t";
        // line 16
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "genre", array()), 'row');
        echo "
\t\t\t";
        // line 17
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "territoire", array()), 'row');
        echo "
\t\t\t";
        // line 18
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "intrigue", array()), 'row');
        echo "
\t\t\t";
        // line 19
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "classe", array()), 'row');
        echo "
\t\t\t
\t\t\t<div class=\"row\">
\t\t\t";
        // line 22
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["classes"]) ? $context["classes"] : $this->getContext($context, "classes")));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["classe"]) {
            // line 23
            echo "\t\t\t\t<div class=\"col-xs-12 col-md-12\">
\t\t\t\t";
            // line 24
            echo twig_include($this->env, $context, "classe/fragment/info.twig", array("classe" => $context["classe"]));
            echo "
\t\t\t\t</div>
\t\t\t";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['classe'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 27
        echo "\t\t\t</div>
\t\t\t";
        // line 28
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'rest');
        echo "
\t\t</fieldset>
\t</form>
</div>
";
    }

    // line 34
    public function block_javascript($context, array $blocks = array())
    {
        // line 35
        echo "
";
        // line 36
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "

";
    }

    public function getTemplateName()
    {
        return "groupe/personnage_add.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  144 => 36,  141 => 35,  138 => 34,  129 => 28,  126 => 27,  109 => 24,  106 => 23,  89 => 22,  83 => 19,  79 => 18,  75 => 17,  71 => 16,  67 => 15,  63 => 14,  59 => 13,  56 => 12,  54 => 11,  50 => 10,  43 => 8,  39 => 6,  36 => 5,  30 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}{{ groupe.nom }}{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="well bs-component">	*/
/* 	<form action="{{ path('groupe.personnage.add', {'index': groupe.id}) }}" method="POST" {{ form_enctype(form) }}>*/
/* 		<fieldset>*/
/* 			<legend>{{ groupe.nom }} - <small>Création d'un personnage</small></legend>*/
/* 			{% form_theme form 'Form/bootstrap_3_layout.html.twig' %}*/
/* */
/* 			{{ form_row(form.nom) }}*/
/* 			{{ form_row(form.surnom) }}*/
/* 			{{ form_row(form.age) }}*/
/* 			{{ form_row(form.genre) }}*/
/* 			{{ form_row(form.territoire) }}*/
/* 			{{ form_row(form.intrigue) }}*/
/* 			{{ form_row(form.classe) }}*/
/* 			*/
/* 			<div class="row">*/
/* 			{% for classe in classes %}*/
/* 				<div class="col-xs-12 col-md-12">*/
/* 				{{ include("classe/fragment/info.twig", {'classe' : classe}) }}*/
/* 				</div>*/
/* 			{%  endfor %}*/
/* 			</div>*/
/* 			{{ form_rest(form) }}*/
/* 		</fieldset>*/
/* 	</form>*/
/* </div>*/
/* {% endblock content %}*/
/* */
/* {% block javascript %}*/
/* */
/* {{ parent() }}*/
/* */
/* {%  endblock javascript %}*/
