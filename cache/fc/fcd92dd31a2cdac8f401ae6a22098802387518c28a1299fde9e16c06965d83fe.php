<?php

/* admin/index.twig */
class __TwigTemplate_d08ecb86e12d7e34210000dfec1d81f89dcdac24b68ec19b898380835b6ebfde extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/index.twig", 1);
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
        echo "Admin";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
<div class=\"container-fluid\">
\t<div class=\"row\">
\t\t<div class=\"col-md-4\">
\t\t\t
\t\t\t<ul class=\"list-group\">
\t\t\t\t<li class=\"list-group-item\"><a href=\"";
        // line 12
        echo $this->env->getExtension('routing')->getPath("admin.log");
        echo "\">Voir les logs</a></li>
\t\t\t\t<li class=\"list-group-item\"><a href=\"";
        // line 13
        echo $this->env->getExtension('routing')->getPath("admin.database.export");
        echo "\">Exporter la base de données</a></li>
\t\t\t\t<li class=\"list-group-item\"><a href=\"";
        // line 14
        echo $this->env->getExtension('routing')->getPath("admin.database.update");
        echo "\">Mettre à jour la base de données</a></li>
\t\t\t</ul>
\t\t\t
\t\t\t<ul class=\"list-group\">
\t\t\t\t<li class=\"list-group-item\"><strong>Taille du cache :</strong> ";
        // line 18
        echo twig_escape_filter($this->env, (isset($context["cacheTotalSpace"]) ? $context["cacheTotalSpace"] : $this->getContext($context, "cacheTotalSpace")), "html", null, true);
        echo " (<a href=\"";
        echo $this->env->getExtension('routing')->getPath("admin.cache.empty");
        echo "\">vider</a>)</li>
\t\t\t\t<li class=\"list-group-item\"><strong>Taille des logs :</strong> ";
        // line 19
        echo twig_escape_filter($this->env, (isset($context["logTotalSpace"]) ? $context["logTotalSpace"] : $this->getContext($context, "logTotalSpace")), "html", null, true);
        echo " (<a href=\"";
        echo $this->env->getExtension('routing')->getPath("admin.log.empty");
        echo "\">vider</a>)</li>
\t\t\t\t<li class=\"list-group-item\"><strong>Taille des documents :</strong> ";
        // line 20
        echo twig_escape_filter($this->env, (isset($context["docTotalSpace"]) ? $context["docTotalSpace"] : $this->getContext($context, "docTotalSpace")), "html", null, true);
        echo "</li>
\t\t\t</ul>
\t\t</div>
\t\t
\t\t<div class=\"col-md-8\">
\t\t\t<div class=\"well well-sm\">
\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<strong>Version de PHP :</strong> ";
        // line 28
        echo twig_escape_filter($this->env, (isset($context["phpVersion"]) ? $context["phpVersion"] : $this->getContext($context, "phpVersion")), "html", null, true);
        echo "
\t\t\t\t\t</li>
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<strong>Taille maximum d'un fichier uploadé</strong> : ";
        // line 31
        echo twig_escape_filter($this->env, ((isset($context["uploadMaxSize"]) ? $context["uploadMaxSize"] : $this->getContext($context, "uploadMaxSize")) / 1024), "html", null, true);
        echo " Ko
\t\t\t\t\t</li>
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<strong>Liste des extensions :</strong>
\t\t\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t\t\t";
        // line 36
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["extensions"]) ? $context["extensions"] : $this->getContext($context, "extensions")));
        foreach ($context['_seq'] as $context["_key"] => $context["extension"]) {
            // line 37
            echo "\t\t\t\t\t\t\t\t<li class=\"list-group-item\">";
            echo twig_escape_filter($this->env, $context["extension"], "html", null, true);
            echo "</li>
\t\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['extension'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "\t\t\t\t\t\t</ul>
\t\t\t\t\t</li>
\t\t\t</div>
\t\t</div>
\t</div>
</div>

";
    }

    public function getTemplateName()
    {
        return "admin/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  111 => 39,  102 => 37,  98 => 36,  90 => 31,  84 => 28,  73 => 20,  67 => 19,  61 => 18,  54 => 14,  50 => 13,  46 => 12,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Admin{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="container-fluid">*/
/* 	<div class="row">*/
/* 		<div class="col-md-4">*/
/* 			*/
/* 			<ul class="list-group">*/
/* 				<li class="list-group-item"><a href="{{ path('admin.log') }}">Voir les logs</a></li>*/
/* 				<li class="list-group-item"><a href="{{ path('admin.database.export') }}">Exporter la base de données</a></li>*/
/* 				<li class="list-group-item"><a href="{{ path('admin.database.update') }}">Mettre à jour la base de données</a></li>*/
/* 			</ul>*/
/* 			*/
/* 			<ul class="list-group">*/
/* 				<li class="list-group-item"><strong>Taille du cache :</strong> {{ cacheTotalSpace }} (<a href="{{ path('admin.cache.empty') }}">vider</a>)</li>*/
/* 				<li class="list-group-item"><strong>Taille des logs :</strong> {{ logTotalSpace }} (<a href="{{ path('admin.log.empty') }}">vider</a>)</li>*/
/* 				<li class="list-group-item"><strong>Taille des documents :</strong> {{ docTotalSpace }}</li>*/
/* 			</ul>*/
/* 		</div>*/
/* 		*/
/* 		<div class="col-md-8">*/
/* 			<div class="well well-sm">*/
/* 				<ul class="list-group">*/
/* 					<li class="list-group-item">*/
/* 						<strong>Version de PHP :</strong> {{ phpVersion }}*/
/* 					</li>*/
/* 					<li class="list-group-item">*/
/* 						<strong>Taille maximum d'un fichier uploadé</strong> : {{ uploadMaxSize / 1024 }} Ko*/
/* 					</li>*/
/* 					<li class="list-group-item">*/
/* 						<strong>Liste des extensions :</strong>*/
/* 						<ul class="list-group">*/
/* 							{% for extension in extensions %}*/
/* 								<li class="list-group-item">{{ extension }}</li>*/
/* 							{% endfor %}*/
/* 						</ul>*/
/* 					</li>*/
/* 			</div>*/
/* 		</div>*/
/* 	</div>*/
/* </div>*/
/* */
/* {% endblock  %}*/
