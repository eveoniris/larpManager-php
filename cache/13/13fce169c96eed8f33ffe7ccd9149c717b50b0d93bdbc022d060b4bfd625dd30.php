<?php

/* public/groupe/accueil.twig */
class __TwigTemplate_da8deb0dd8479d10607eb21e7573e62fb97ef44d73579c96325798d66b7a4f90 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "public/groupe/accueil.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
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
        echo "Groupe";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
\t<ol class=\"breadcrumb\">
\t\t<li><a href=\"";
        // line 8
        echo $this->env->getExtension('routing')->getPath("homepage");
        echo "\">Page d'accueil</a></li>
\t\t<li class=\"active\">Votre groupe</li>
\t</ol>
\t
\t<div class=\"well bs-component\">
\t\t";
        // line 13
        if ( !$this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "etatCivil", array())) {
            // line 14
            echo "\t\t\tVous devez remplir votre état civil avant de pouvoir rejoindre un groupe
\t\t";
        } else {
            // line 16
            echo "\t\t\t";
            if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupes", array())) == 0)) {
                // line 17
                echo "\t\t\t\t<div class=\"header\">
\t\t\t\t\t<h5>";
                // line 18
                echo $this->env->getExtension('translator')->getTranslator()->trans("subscribe_group_title", array(), "messages");
                echo "</h5>
\t\t\t\t</div>
\t\t\t\t\t
\t\t\t\t<p>";
                // line 21
                echo $this->env->getExtension('translator')->getTranslator()->trans("subscribe_group_explain", array(), "messages");
                echo "</p>
\t\t\t\t
\t\t\t\t";
                // line 23
                echo twig_include($this->env, $context, "public/groupe/fragment/form_groupe.twig", array("form" => (isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "action" => $this->env->getExtension('routing')->getPath("homepage.inscription")));
                echo "
\t\t\t";
            } else {
                // line 25
                echo "\t\t\t\t";
                // line 26
                echo "\t\t\t\t";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupes", array()));
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
                foreach ($context['_seq'] as $context["_key"] => $context["groupe"]) {
                    // line 27
                    echo "\t\t\t\t\t";
                    echo twig_include($this->env, $context, "public/groupe/fragment/groupe.twig", array("groupe" => $context["groupe"]));
                    echo "
\t\t\t\t";
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
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['groupe'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 29
                echo "\t\t\t";
            }
            // line 30
            echo "\t\t";
        }
        // line 31
        echo "\t</div>

";
    }

    public function getTemplateName()
    {
        return "public/groupe/accueil.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 31,  118 => 30,  115 => 29,  98 => 27,  80 => 26,  78 => 25,  73 => 23,  68 => 21,  62 => 18,  59 => 17,  56 => 16,  52 => 14,  50 => 13,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Groupe{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage') }}">Page d'accueil</a></li>*/
/* 		<li class="active">Votre groupe</li>*/
/* 	</ol>*/
/* 	*/
/* 	<div class="well bs-component">*/
/* 		{% if not app.user.etatCivil %}*/
/* 			Vous devez remplir votre état civil avant de pouvoir rejoindre un groupe*/
/* 		{% else %}*/
/* 			{% if app.user.groupes|length == 0 %}*/
/* 				<div class="header">*/
/* 					<h5>{% trans %}subscribe_group_title{% endtrans %}</h5>*/
/* 				</div>*/
/* 					*/
/* 				<p>{% trans %}subscribe_group_explain{% endtrans %}</p>*/
/* 				*/
/* 				{{ include("public/groupe/fragment/form_groupe.twig",{'form': form,'action': path('homepage.inscription')}) }}*/
/* 			{% else %}*/
/* 				{# Détail des groupes#}*/
/* 				{% for groupe in app.user.groupes %}*/
/* 					{{ include("public/groupe/fragment/groupe.twig",{'groupe':groupe}) }}*/
/* 				{% endfor %}*/
/* 			{% endif %}*/
/* 		{% endif %}*/
/* 	</div>*/
/* */
/* {% endblock content %}*/
