<?php

/* public/discuter.twig */
class __TwigTemplate_5f16bd3cf648695d6840b8044dbdfd18ae9aab2745fa92472f80a6db009696a4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "public/discuter.twig", 1);
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
        echo "Discuter";
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
\t\t<li class=\"active\">Discuter</li>
\t</ol>
\t
\t<div class=\"well bs-component\">
\t
\t\t<blockquote>Vous trouverez les liens vers les différents forums que vous pouvez utiliser ci-dessous.</blockquote>
\t\t\t\t\t\t\t\t
\t\t<div class=\"header\"><h5>Forums</h5></div>
\t\t<ul class=\"list-group\">
\t\t\t<li class=\"list-group-item\">
\t\t\t\t<a href=\"";
        // line 19
        echo $this->env->getExtension('routing')->getPath("forum");
        echo "\">
\t\t\t\t\t<i class=\"fa fa-comment\"></i>
\t\t\t\t\tForum
\t\t\t\t</a>
\t\t\t</li>
\t\t\t";
        // line 24
        if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupes", array())) > 0)) {
            // line 25
            echo "\t\t\t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupes", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["groupe"]) {
                // line 26
                echo "\t\t\t\t\t";
                if ($this->getAttribute($context["groupe"], "topic", array())) {
                    // line 27
                    echo "\t\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t\t<a href=\"";
                    // line 28
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("forum.topic", array("index" => $this->getAttribute($this->getAttribute($context["groupe"], "topic", array()), "id", array()))), "html", null, true);
                    echo "\">
\t\t\t\t\t\t\t\t<i class=\"fa fa-comment\"></i>
\t\t\t\t\t\t\t\tForum du groupe \"";
                    // line 30
                    echo twig_escape_filter($this->env, $this->getAttribute($context["groupe"], "nom", array()), "html", null, true);
                    echo "\"
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                }
                // line 34
                echo "\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['groupe'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 35
            echo "\t\t\t";
        }
        // line 36
        echo "\t\t\t";
        if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array())) {
            // line 37
            echo "\t\t\t\t";
            if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array()), "territoire", array())) {
                // line 38
                echo "\t\t\t\t\t";
                if ($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array()), "territoire", array()), "topic", array())) {
                    // line 39
                    echo "\t\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t\t<a href=\"";
                    // line 40
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("forum.topic", array("index" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array()), "territoire", array()), "topic", array()), "id", array()))), "html", null, true);
                    echo "\">
\t\t\t\t\t\t\t\t<i class=\"fa fa-comment\"></i>
\t\t\t\t\t\t\t\tForum du territoire \"";
                    // line 42
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array()), "territoire", array()), "nom", array()), "html", null, true);
                    echo "\"
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                }
                // line 46
                echo "\t\t\t\t";
            }
            // line 47
            echo "\t\t\t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array()), "personnagesReligions", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["personnageReligion"]) {
                // line 48
                echo "\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<a href=\"";
                // line 49
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("forum.topic", array("index" => $this->getAttribute($this->getAttribute($this->getAttribute($context["personnageReligion"], "religion", array()), "topic", array()), "id", array()))), "html", null, true);
                echo "\">
\t\t\t\t\t\t\t<i class=\"fa fa-comment\"></i>\t\t\t\t\t\t\t
\t\t\t\t\t\t\tForum de la religion \"";
                // line 51
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["personnageReligion"], "religion", array()), "label", array()), "html", null, true);
                echo "\"
\t\t\t\t\t\t</a>
\t\t\t\t\t</li>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['personnageReligion'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 55
            echo "\t\t\t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array()), "membres", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["membre"]) {
                // line 56
                echo "\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<a href=\"";
                // line 57
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("forum.topic", array("index" => $this->getAttribute($this->getAttribute($this->getAttribute($context["membre"], "secondaryGroup", array()), "topic", array()), "id", array()))), "html", null, true);
                echo "\">
\t\t\t\t\t\t\t<i class=\"fa fa-comment\"></i>
\t\t\t\t\t\t\tForum du groupe secondaire \"";
                // line 59
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["membre"], "secondaryGroup", array()), "label", array()), "html", null, true);
                echo "\"
\t\t\t\t\t\t</a>
\t\t\t\t\t</li>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['membre'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 63
            echo "\t\t\t\t
\t\t\t";
        }
        // line 65
        echo "\t\t</ul>
\t\t
\t\t<div class=\"header\"><h5>Mails</h5></div>
\t\t
\t\t<ul class=\"list-group\">
\t\t\t<li class=\"list-group-item\"><a href=\"";
        // line 70
        echo $this->env->getExtension('routing')->getPath("user.messagerie");
        echo "\">Ma messagerie</a></li>
\t\t
\t\t\t";
        // line 72
        if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupes", array())) > 0)) {
            // line 73
            echo "\t\t\t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupes", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["groupe"]) {
                // line 74
                echo "\t\t\t\t\t";
                if ($this->getAttribute($context["groupe"], "scenariste", array())) {
                    echo "<li class=\"list-group-item\"><strong>Votre scénariste est </strong>";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["groupe"], "scenariste", array()), "username", array()), "html", null, true);
                    echo " / ";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["groupe"], "scenariste", array()), "email", array()), "html", null, true);
                    echo "</li> ";
                }
                // line 75
                echo "\t\t\t\t\t";
                if ($this->getAttribute($context["groupe"], "responsable", array())) {
                    echo "<li class=\"list-group-item\"><strong>Votre responsable de groupe est </strong>";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["groupe"], "responsable", array()), "username", array()), "html", null, true);
                    echo " / ";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["groupe"], "responsable", array()), "email", array()), "html", null, true);
                    echo "</li>";
                }
                echo "\t\t\t
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['groupe'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 77
            echo "\t\t\t";
        }
        // line 78
        echo "\t\t</ul>
\t</div>
\t
";
    }

    public function getTemplateName()
    {
        return "public/discuter.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  221 => 78,  218 => 77,  203 => 75,  194 => 74,  189 => 73,  187 => 72,  182 => 70,  175 => 65,  171 => 63,  161 => 59,  156 => 57,  153 => 56,  148 => 55,  138 => 51,  133 => 49,  130 => 48,  125 => 47,  122 => 46,  115 => 42,  110 => 40,  107 => 39,  104 => 38,  101 => 37,  98 => 36,  95 => 35,  89 => 34,  82 => 30,  77 => 28,  74 => 27,  71 => 26,  66 => 25,  64 => 24,  56 => 19,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Discuter{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage') }}">Page d'accueil</a></li>*/
/* 		<li class="active">Discuter</li>*/
/* 	</ol>*/
/* 	*/
/* 	<div class="well bs-component">*/
/* 	*/
/* 		<blockquote>Vous trouverez les liens vers les différents forums que vous pouvez utiliser ci-dessous.</blockquote>*/
/* 								*/
/* 		<div class="header"><h5>Forums</h5></div>*/
/* 		<ul class="list-group">*/
/* 			<li class="list-group-item">*/
/* 				<a href="{{ path('forum') }}">*/
/* 					<i class="fa fa-comment"></i>*/
/* 					Forum*/
/* 				</a>*/
/* 			</li>*/
/* 			{% if app.user.groupes|length > 0 %}*/
/* 				{% for groupe in app.user.groupes %}*/
/* 					{% if groupe.topic %}*/
/* 						<li class="list-group-item">*/
/* 							<a href="{{ path('forum.topic', {'index': groupe.topic.id}) }}">*/
/* 								<i class="fa fa-comment"></i>*/
/* 								Forum du groupe "{{ groupe.nom }}"*/
/* 							</a>*/
/* 						</li>*/
/* 					{% endif %}*/
/* 				{% endfor %}*/
/* 			{% endif %}*/
/* 			{% if app.user.personnage %}*/
/* 				{% if app.user.personnage.territoire %}*/
/* 					{% if app.user.personnage.territoire.topic %}*/
/* 						<li class="list-group-item">*/
/* 							<a href="{{ path('forum.topic', {'index': app.user.personnage.territoire.topic.id}) }}">*/
/* 								<i class="fa fa-comment"></i>*/
/* 								Forum du territoire "{{ app.user.personnage.territoire.nom }}"*/
/* 							</a>*/
/* 						</li>*/
/* 					{% endif %}*/
/* 				{% endif %}*/
/* 				{% for personnageReligion in app.user.personnage.personnagesReligions %}*/
/* 					<li class="list-group-item">*/
/* 						<a href="{{ path('forum.topic',{'index': personnageReligion.religion.topic.id }) }}">*/
/* 							<i class="fa fa-comment"></i>							*/
/* 							Forum de la religion "{{ personnageReligion.religion.label }}"*/
/* 						</a>*/
/* 					</li>*/
/* 				{% endfor %}*/
/* 				{% for membre in app.user.personnage.membres %}*/
/* 					<li class="list-group-item">*/
/* 						<a href="{{ path('forum.topic',{'index': membre.secondaryGroup.topic.id}) }}">*/
/* 							<i class="fa fa-comment"></i>*/
/* 							Forum du groupe secondaire "{{ membre.secondaryGroup.label }}"*/
/* 						</a>*/
/* 					</li>*/
/* 				{% endfor %}*/
/* 				*/
/* 			{% endif %}*/
/* 		</ul>*/
/* 		*/
/* 		<div class="header"><h5>Mails</h5></div>*/
/* 		*/
/* 		<ul class="list-group">*/
/* 			<li class="list-group-item"><a href="{{ path('user.messagerie') }}">Ma messagerie</a></li>*/
/* 		*/
/* 			{% if app.user.groupes|length > 0 %}*/
/* 				{% for groupe in app.user.groupes %}*/
/* 					{% if groupe.scenariste %}<li class="list-group-item"><strong>Votre scénariste est </strong>{{ groupe.scenariste.username }} / {{ groupe.scenariste.email }}</li> {% endif %}*/
/* 					{% if groupe.responsable %}<li class="list-group-item"><strong>Votre responsable de groupe est </strong>{{ groupe.responsable.username }} / {{ groupe.responsable.email }}</li>{% endif %}			*/
/* 				{% endfor %}*/
/* 			{% endif %}*/
/* 		</ul>*/
/* 	</div>*/
/* 	*/
/* {% endblock content %}*/
