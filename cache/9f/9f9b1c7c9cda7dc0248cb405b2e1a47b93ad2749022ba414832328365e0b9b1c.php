<?php

/* admin/groupe/update.twig */
class __TwigTemplate_2c55c48911c79413aae8be2997c46bdf99cde4922f052c300af9cc8bf61feefc extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/groupe/update.twig", 1);
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
        echo "Groupes";
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
        echo "\">Accueil</a></li>
\t\t<li><a href=\"";
        // line 9
        echo $this->env->getExtension('routing')->getPath("groupe.admin.list");
        echo "\">Liste des groupes</a></li>
\t\t<li class=\"active\">Modification d'un groupe</li>
\t</ol>

\t<div class=\"well bs-component\">
\t<form action=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.update", array("index" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()))), "html", null, true);
        echo "\" method=\"POST\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo " novalidate>
\t\t<fieldset>
\t\t\t<legend>Modification d'un groupe</legend>
\t\t\t";
        // line 17
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "Form/bootstrap_3_layout.html.twig"));
        // line 18
        echo "\t\t\t
\t\t\t\t\t<div class=\"panel panel-default\">
\t\t\t\t\t\t<div class=\"panel-heading\">
\t\t\t\t\t\t\t<h6>Informations générales</h6>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"panel-body\">
\t\t\t\t\t\t\t";
        // line 24
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "nom", array()), 'row');
        echo "
\t\t\t\t\t\t\t";
        // line 25
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "description", array()), 'row');
        echo "
\t\t\t\t\t\t\t";
        // line 26
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "territoires", array()), 'row');
        echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"panel panel-default\">
\t\t\t\t\t\t<div class=\"panel-heading\">
\t\t\t\t\t\t\t<h6>Informations techniques</h6>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"panel-body\">
\t\t\t\t\t\t\t";
        // line 35
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numero", array()), 'row');
        echo "
\t\t\t\t\t\t\t";
        // line 36
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "scenariste", array()), 'row');
        echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"panel panel-default\">
\t\t\t\t\t\t<div class=\"panel-heading\">
\t\t\t\t\t\t\t<h6>Chef du groupe</h6>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"panel-body\">
\t\t\t\t\t\t\t<p>Vous devez choisir au moins un responsable du groupe. C'est ce joueur qui aura la charge d'inviter les autres membres du groupe en leur communiquant le code défini ci-dessous.</p>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t\t\t";
        // line 49
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "responsable", array()), 'row');
        echo "
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t\t\t";
        // line 52
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "code", array()), 'row');
        echo "
\t\t\t\t\t\t\t\t<a href=\"#\">Envoyer un mail avec le code au responsable</a>
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t</ul>
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"panel panel-default\">
\t\t\t\t\t\t<div class=\"panel-heading\">
\t\t\t\t\t\t\t<h6>Type de jeu</h6>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"panel-body\">
\t\t\t\t\t\t\t";
        // line 63
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "jeuStrategique", array()), 'row');
        echo "
\t\t\t\t\t\t\t";
        // line 64
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "jeuMaritime", array()), 'row');
        echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"panel panel-default\">
\t\t\t\t\t\t<div class=\"panel-heading\">
\t\t\t\t\t\t\t<h6>Composition du groupe</h6>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"panel-body\">
\t\t\t\t\t\t\t<p>Composez votre groupe avec une ou plusieurs classes. Ces classes seront disponibles pour les joueurs.</p>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<ul class=\"list-group groupeClasses\" data-prototype=\"";
        // line 75
        echo twig_escape_filter($this->env, $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "groupeClasses", array()), "vars", array()), "prototype", array()), 'widget'));
        echo "\">
\t\t\t\t\t    \t";
        // line 76
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "groupeClasses", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["groupeClasse"]) {
            // line 77
            echo "\t\t\t\t\t           \t<li class=\"list-group-item\">";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($context["groupeClasse"], "classe", array()), 'widget');
            echo "</li>
\t\t\t\t\t       \t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['groupeClasse'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 79
        echo "\t\t\t\t\t\t</ul>
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"panel panel-default\">
\t\t\t\t\t\t<div class=\"panel-body\">
\t\t\t\t\t\t\t";
        // line 84
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "gns", array()), 'row');
        echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t\t
\t\t\t
\t\t\t";
        // line 89
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'rest');
        echo "
\t\t</fieldset>
\t</form>
\t</div>
\t\t
";
    }

    // line 97
    public function block_javascript($context, array $blocks = array())
    {
        // line 98
        echo "
\t";
        // line 99
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
\t
\t";
        // line 102
        echo "\t   
\t<script src=\"";
        // line 103
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/tinymce/tinymce.min.js\"></script>

\t<script type=\"text/javascript\">
\t\ttinyMCE.init({
\t\t\t\tmode: \"textareas\",
\t\t\t\ttheme: \"modern\",
\t\t\t\tplugins : \"spellchecker,insertdatetime,preview\", 
\t\t});
\t\t
\t</script>
\t
\t<script>

\t\tfunction addGroupeClasseForm(collectionHolder, \$newLinkLi) {
\t\t    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
\t\t    var prototype = collectionHolder.attr('data-prototype');

\t\t    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
\t\t    // la longueur de la collection courante
\t\t    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
\t
\t\t    // Affiche le formulaire dans la page dans un li, avant le lien \"ajouter un tag\"
\t\t    var \$newFormLi = \$('<li class=\"list-group-item\"></li>').append(newForm);
\t\t    \$newLinkLi.before(\$newFormLi);

\t\t\t // ajoute un lien de suppression au nouveau formulaire
\t\t    addGroupeClasseFormDeleteLink(\$newFormLi);
\t\t}\t\t

\t\tfunction addGroupeClasseFormDeleteLink(\$groupeClasseFormLi) {
\t\t    var \$removeFormA = \$('<span class=\"input-group-btn\"><a class=\"btn btn-link\" href=\"#\"><span class=\"glyphicon glyphicon-remove-circle\" aria-hidden=\"true\"></span></a></span>');
\t\t    \$('select',\$groupeClasseFormLi).wrap('<div class=\"input-group\"></div>');
\t\t    \$('select',\$groupeClasseFormLi).after(\$removeFormA);

\t\t    \$removeFormA.on('click', function(e) {
\t\t        // empêche le lien de créer un « # » dans l'URL
\t\t        e.preventDefault();

\t\t        // supprime l'élément li pour le formulaire de tag
\t\t        \$groupeClasseFormLi.remove();
\t\t    });
\t\t}
\t
\t\t// Récupère le div qui contient la collection de tags
\t\tvar collectionHolder = \$('ul.groupeClasses');
\t\t
\t\t// ajoute un lien « add a tag »
\t\tvar \$addGroupeClasseLink = \$('<a href=\"#\" class=\"add_groupeClasse_link\">Ajouter une classe</a>');
\t\tvar \$newLinkLi = \$('<li class=\"list-group-item\"></li>').append(\$addGroupeClasseLink);
\t\t
\t\tjQuery(document).ready(function() {

\t\t\t// ajoute un lien de suppression à tous les éléments li de
\t\t    // formulaires de tag existants
\t\t    collectionHolder.find('li').each(function() {
\t\t        addGroupeClasseFormDeleteLink(\$(this));
\t\t    });
\t\t    
\t\t    // ajoute l'ancre « ajouter un tag » et li à la balise ul
\t\t    collectionHolder.append(\$newLinkLi);
\t\t
\t\t    \$addGroupeClasseLink.on('click', function(e) {
\t\t        // empêche le lien de créer un « # » dans l'URL
\t\t        e.preventDefault();
\t\t
\t\t        // ajoute un nouveau formulaire tag (voir le prochain bloc de code)
\t\t        addGroupeClasseForm(collectionHolder, \$newLinkLi);
\t\t    });
\t\t});
</script>
";
    }

    public function getTemplateName()
    {
        return "admin/groupe/update.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  207 => 103,  204 => 102,  199 => 99,  196 => 98,  193 => 97,  183 => 89,  175 => 84,  168 => 79,  159 => 77,  155 => 76,  151 => 75,  137 => 64,  133 => 63,  119 => 52,  113 => 49,  97 => 36,  93 => 35,  81 => 26,  77 => 25,  73 => 24,  65 => 18,  63 => 17,  55 => 14,  47 => 9,  43 => 8,  39 => 6,  36 => 5,  30 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Groupes{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage') }}">Accueil</a></li>*/
/* 		<li><a href="{{ path("groupe.admin.list") }}">Liste des groupes</a></li>*/
/* 		<li class="active">Modification d'un groupe</li>*/
/* 	</ol>*/
/* */
/* 	<div class="well bs-component">*/
/* 	<form action="{{ path('groupe.update', {'index': groupe.id}) }}" method="POST" {{ form_enctype(form) }} novalidate>*/
/* 		<fieldset>*/
/* 			<legend>Modification d'un groupe</legend>*/
/* 			{% form_theme form 'Form/bootstrap_3_layout.html.twig' %}*/
/* 			*/
/* 					<div class="panel panel-default">*/
/* 						<div class="panel-heading">*/
/* 							<h6>Informations générales</h6>*/
/* 						</div>*/
/* 						<div class="panel-body">*/
/* 							{{ form_row(form.nom) }}*/
/* 							{{ form_row(form.description) }}*/
/* 							{{ form_row(form.territoires) }}*/
/* 						</div>*/
/* 					</div>*/
/* */
/* 					<div class="panel panel-default">*/
/* 						<div class="panel-heading">*/
/* 							<h6>Informations techniques</h6>*/
/* 						</div>*/
/* 						<div class="panel-body">*/
/* 							{{ form_row(form.numero) }}*/
/* 							{{ form_row(form.scenariste) }}*/
/* 						</div>*/
/* 					</div>*/
/* */
/* 					<div class="panel panel-default">*/
/* 						<div class="panel-heading">*/
/* 							<h6>Chef du groupe</h6>*/
/* 						</div>*/
/* 						<div class="panel-body">*/
/* 							<p>Vous devez choisir au moins un responsable du groupe. C'est ce joueur qui aura la charge d'inviter les autres membres du groupe en leur communiquant le code défini ci-dessous.</p>*/
/* 						</div>*/
/* 						<ul class="list-group">*/
/* 							<li class="list-group-item">*/
/* 								{{ form_row(form.responsable) }}*/
/* 							</li>*/
/* 							<li class="list-group-item">*/
/* 								{{ form_row(form.code) }}*/
/* 								<a href="#">Envoyer un mail avec le code au responsable</a>*/
/* 							</li>*/
/* 						</ul>*/
/* 					</div>*/
/* */
/* 					<div class="panel panel-default">*/
/* 						<div class="panel-heading">*/
/* 							<h6>Type de jeu</h6>*/
/* 						</div>*/
/* 						<div class="panel-body">*/
/* 							{{ form_row(form.jeuStrategique) }}*/
/* 							{{ form_row(form.jeuMaritime) }}*/
/* 						</div>*/
/* 					</div>*/
/* */
/* 					<div class="panel panel-default">*/
/* 						<div class="panel-heading">*/
/* 							<h6>Composition du groupe</h6>*/
/* 						</div>*/
/* 						<div class="panel-body">*/
/* 							<p>Composez votre groupe avec une ou plusieurs classes. Ces classes seront disponibles pour les joueurs.</p>*/
/* 						</div>*/
/* 						<ul class="list-group groupeClasses" data-prototype="{{ form_widget(form.groupeClasses.vars.prototype)|e }}">*/
/* 					    	{% for groupeClasse in form.groupeClasses %}*/
/* 					           	<li class="list-group-item">{{ form_widget(groupeClasse.classe) }}</li>*/
/* 					       	{% endfor %}*/
/* 						</ul>*/
/* 					</div>*/
/* */
/* 					<div class="panel panel-default">*/
/* 						<div class="panel-body">*/
/* 							{{ form_row(form.gns) }}*/
/* 						</div>*/
/* 					</div>*/
/* 					*/
/* 			*/
/* 			{{ form_rest(form) }}*/
/* 		</fieldset>*/
/* 	</form>*/
/* 	</div>*/
/* 		*/
/* {% endblock content %}*/
/* */
/* */
/* {% block javascript %}*/
/* */
/* 	{{  parent() }}*/
/* 	*/
/* 	{# inclusion du plugin tinymce pour la saisie de la description #}*/
/* 	   */
/* 	<script src="{{ app.request.basepath }}/js/tinymce/tinymce.min.js"></script>*/
/* */
/* 	<script type="text/javascript">*/
/* 		tinyMCE.init({*/
/* 				mode: "textareas",*/
/* 				theme: "modern",*/
/* 				plugins : "spellchecker,insertdatetime,preview", */
/* 		});*/
/* 		*/
/* 	</script>*/
/* 	*/
/* 	<script>*/
/* */
/* 		function addGroupeClasseForm(collectionHolder, $newLinkLi) {*/
/* 		    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt*/
/* 		    var prototype = collectionHolder.attr('data-prototype');*/
/* */
/* 		    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur*/
/* 		    // la longueur de la collection courante*/
/* 		    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);*/
/* 	*/
/* 		    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un tag"*/
/* 		    var $newFormLi = $('<li class="list-group-item"></li>').append(newForm);*/
/* 		    $newLinkLi.before($newFormLi);*/
/* */
/* 			 // ajoute un lien de suppression au nouveau formulaire*/
/* 		    addGroupeClasseFormDeleteLink($newFormLi);*/
/* 		}		*/
/* */
/* 		function addGroupeClasseFormDeleteLink($groupeClasseFormLi) {*/
/* 		    var $removeFormA = $('<span class="input-group-btn"><a class="btn btn-link" href="#"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a></span>');*/
/* 		    $('select',$groupeClasseFormLi).wrap('<div class="input-group"></div>');*/
/* 		    $('select',$groupeClasseFormLi).after($removeFormA);*/
/* */
/* 		    $removeFormA.on('click', function(e) {*/
/* 		        // empêche le lien de créer un « # » dans l'URL*/
/* 		        e.preventDefault();*/
/* */
/* 		        // supprime l'élément li pour le formulaire de tag*/
/* 		        $groupeClasseFormLi.remove();*/
/* 		    });*/
/* 		}*/
/* 	*/
/* 		// Récupère le div qui contient la collection de tags*/
/* 		var collectionHolder = $('ul.groupeClasses');*/
/* 		*/
/* 		// ajoute un lien « add a tag »*/
/* 		var $addGroupeClasseLink = $('<a href="#" class="add_groupeClasse_link">Ajouter une classe</a>');*/
/* 		var $newLinkLi = $('<li class="list-group-item"></li>').append($addGroupeClasseLink);*/
/* 		*/
/* 		jQuery(document).ready(function() {*/
/* */
/* 			// ajoute un lien de suppression à tous les éléments li de*/
/* 		    // formulaires de tag existants*/
/* 		    collectionHolder.find('li').each(function() {*/
/* 		        addGroupeClasseFormDeleteLink($(this));*/
/* 		    });*/
/* 		    */
/* 		    // ajoute l'ancre « ajouter un tag » et li à la balise ul*/
/* 		    collectionHolder.append($newLinkLi);*/
/* 		*/
/* 		    $addGroupeClasseLink.on('click', function(e) {*/
/* 		        // empêche le lien de créer un « # » dans l'URL*/
/* 		        e.preventDefault();*/
/* 		*/
/* 		        // ajoute un nouveau formulaire tag (voir le prochain bloc de code)*/
/* 		        addGroupeClasseForm(collectionHolder, $newLinkLi);*/
/* 		    });*/
/* 		});*/
/* </script>*/
/* {% endblock javascript %}*/
