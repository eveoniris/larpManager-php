<?php

/* public/personnageSecondaire/list.twig */
class __TwigTemplate_89855f0f8f2fd2c60ed08a3afe0d9752e69e08d14ed8975f8e63e8b850b73aa2 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "public/personnageSecondaire/list.twig", 1);
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
        echo "Personnages secondaires";
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
\t\t<li class=\"active\">Liste des archètypes de personnage secondaire</li>
\t</ol>
\t
\t<div class=\"well bs-component\">
\t\t<p>Le personnage secondaire est un archétype que vous pouvez utiliser dans le cas 
\t\t\tou <strong>votre personnage principal viendrait à trépasser</strong> (ce que nous ne souhaitons pas, mais l'Hyborée
\t\t\t est un monde violent dans lequel les pires choses sont possibles) ou <strong>si vous souhaitez l'utiliser
\t\t\t  dans une instance à la place de l'autre</strong> (plus utile, ou toute autre raison)</p>
\t\t\t\t\t\t\t\t  
\t\t";
        // line 18
        if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnageSecondaire", array())) {
            // line 19
            echo "\t\t\t<h6>Personnage secondaire : 
\t\t\t\t<small>";
            // line 20
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnageSecondaire", array()), "classe", array()), "label", array()), "html", null, true);
            echo "
\t\t\t\t\t";
            // line 21
            echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnageSecondaire", array()), "competences", array()), " / "), "html", null, true);
            echo "</small>
\t\t\t</h6>
\t\t\t<p><a href=\"";
            // line 23
            echo $this->env->getExtension('routing')->getPath("personnageSecondaire.choice");
            echo "\">Modifier son personnage secondaire</a></p>
\t\t";
        } else {
            // line 25
            echo "\t\t\t<p>Vous n'avez pas encore choisi votre personnage secondaire.</p>
\t\t\t<p><a href=\"";
            // line 26
            echo $this->env->getExtension('routing')->getPath("personnageSecondaire.choice");
            echo "\">Choisir son personnage secondaire</a></p>
\t\t";
        }
        // line 28
        echo "
\t</div>
\t
\t<div class=\"well bs-component\">
\t\t<table class=\"table\">
\t\t\t<caption >
\t\t\t\t<h5>Détail des archétypes</h5>
\t\t\t</caption>
\t\t\t<thead>
\t\t\t\t<tr>
\t\t\t\t\t<th>Label</th>
\t\t\t\t\t<th>Compétences</th>
\t\t\t\t</tr>
\t\t\t</thead>\t
\t\t\t<tbody>
\t\t\t\t";
        // line 43
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["personnageSecondaires"]) ? $context["personnageSecondaires"] : $this->getContext($context, "personnageSecondaires")));
        foreach ($context['_seq'] as $context["_key"] => $context["personnageSecondaire"]) {
            // line 44
            echo "\t\t\t\t\t<tr>
\t\t\t\t\t\t<td>";
            // line 45
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["personnageSecondaire"], "classe", array()), "label", array()), "html", null, true);
            echo "</td>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t";
            // line 47
            echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute($context["personnageSecondaire"], "competences", array()), " / "), "html", null, true);
            echo "
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['personnageSecondaire'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 51
        echo "\t\t\t</tbody>
\t\t</table>
\t</div>
";
    }

    public function getTemplateName()
    {
        return "public/personnageSecondaire/list.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 51,  111 => 47,  106 => 45,  103 => 44,  99 => 43,  82 => 28,  77 => 26,  74 => 25,  69 => 23,  64 => 21,  60 => 20,  57 => 19,  55 => 18,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Personnages secondaires{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage') }}">Page d'accueil</a></li>*/
/* 		<li class="active">Liste des archètypes de personnage secondaire</li>*/
/* 	</ol>*/
/* 	*/
/* 	<div class="well bs-component">*/
/* 		<p>Le personnage secondaire est un archétype que vous pouvez utiliser dans le cas */
/* 			ou <strong>votre personnage principal viendrait à trépasser</strong> (ce que nous ne souhaitons pas, mais l'Hyborée*/
/* 			 est un monde violent dans lequel les pires choses sont possibles) ou <strong>si vous souhaitez l'utiliser*/
/* 			  dans une instance à la place de l'autre</strong> (plus utile, ou toute autre raison)</p>*/
/* 								  */
/* 		{% if app.user.personnageSecondaire %}*/
/* 			<h6>Personnage secondaire : */
/* 				<small>{{ app.user.personnageSecondaire.classe.label }}*/
/* 					{{ app.user.personnageSecondaire.competences|join(' / ') }}</small>*/
/* 			</h6>*/
/* 			<p><a href="{{ path('personnageSecondaire.choice') }}">Modifier son personnage secondaire</a></p>*/
/* 		{% else %}*/
/* 			<p>Vous n'avez pas encore choisi votre personnage secondaire.</p>*/
/* 			<p><a href="{{ path('personnageSecondaire.choice') }}">Choisir son personnage secondaire</a></p>*/
/* 		{% endif %}*/
/* */
/* 	</div>*/
/* 	*/
/* 	<div class="well bs-component">*/
/* 		<table class="table">*/
/* 			<caption >*/
/* 				<h5>Détail des archétypes</h5>*/
/* 			</caption>*/
/* 			<thead>*/
/* 				<tr>*/
/* 					<th>Label</th>*/
/* 					<th>Compétences</th>*/
/* 				</tr>*/
/* 			</thead>	*/
/* 			<tbody>*/
/* 				{%  for personnageSecondaire in personnageSecondaires %}*/
/* 					<tr>*/
/* 						<td>{{ personnageSecondaire.classe.label }}</td>*/
/* 						<td>*/
/* 							{{ personnageSecondaire.competences|join(' / ') }}*/
/* 						</td>*/
/* 					</tr>*/
/* 				{%  endfor %}*/
/* 			</tbody>*/
/* 		</table>*/
/* 	</div>*/
/* {% endblock content %}*/
