<?php

/* homepage/not_connected.twig */
class __TwigTemplate_de39ae1c0f9b4499ce8730aee9ef5e84d1336cea1606be56ed16bd38171ff52b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 6
        $this->parent = $this->loadTemplate("layout.twig", "homepage/not_connected.twig", 6);
        $this->blocks = array(
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

    // line 8
    public function block_content($context, array $blocks = array())
    {
        // line 9
        echo "
<div class=\"row\">

\t<div class=\"col-xs-6 col-md-6\">
\t
\t\t<div class=\"thumbnail\">
\t\t\t<div class=\"caption\">
\t\t\t\t<center><h3>Déjà enregistré ?</h3></center>
\t\t\t</div>
\t\t\t<p>
\t\t\t\t<center>
\t\t\t\t\tConnectez-vous pour acceder à l'interface larpManager.
\t\t\t\t</center>
\t\t\t</p>
\t\t\t<p>
\t\t\t\t<center>
\t\t\t\t\t<a class=\"btn btn-primary\" href=\"";
        // line 25
        echo $this->env->getExtension('routing')->getPath("user.login");
        echo "\">Connexion</a>
\t\t\t\t</center>
\t\t\t</p>
\t\t</div>
\t</div>
\t
\t<div class=\"col-xs-6 col-md-6\">
\t
\t\t<div class=\"thumbnail\">
\t\t\t<div class=\"caption\">
\t\t\t\t<center><h3>Nouvel utilisateur ?</h3></center>
\t\t\t</div>
\t\t\t<p>
\t\t\t\t<center>
\t\t\t\t\tEnregistrez-vous pour créer votre personnage et participer aux grandeur natures d'eve-oniris</p>
\t\t\t\t</center>
\t\t\t<p>
\t\t\t\t<center>
\t\t\t\t\t<a class=\"btn btn-primary\" href=\"";
        // line 43
        echo $this->env->getExtension('routing')->getPath("user.register");
        echo "\">S'enregistrer</a>
\t\t\t\t</center>
\t\t\t</p>
\t\t</div>
\t</div>
</div>

";
    }

    public function getTemplateName()
    {
        return "homepage/not_connected.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 43,  49 => 25,  31 => 9,  28 => 8,  11 => 6,);
    }
}
/* {# page d'acceuil lorsque l'utilisateur n'est pas connecté.*/
/*    dans ce cas, l'utilisateur ne peux faire que deux choix :*/
/*    	- se connecter*/
/*    	- ou se créer un compte #}*/
/*    	*/
/* {% extends "layout.twig" %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="row">*/
/* */
/* 	<div class="col-xs-6 col-md-6">*/
/* 	*/
/* 		<div class="thumbnail">*/
/* 			<div class="caption">*/
/* 				<center><h3>Déjà enregistré ?</h3></center>*/
/* 			</div>*/
/* 			<p>*/
/* 				<center>*/
/* 					Connectez-vous pour acceder à l'interface larpManager.*/
/* 				</center>*/
/* 			</p>*/
/* 			<p>*/
/* 				<center>*/
/* 					<a class="btn btn-primary" href="{{ path('user.login') }}">Connexion</a>*/
/* 				</center>*/
/* 			</p>*/
/* 		</div>*/
/* 	</div>*/
/* 	*/
/* 	<div class="col-xs-6 col-md-6">*/
/* 	*/
/* 		<div class="thumbnail">*/
/* 			<div class="caption">*/
/* 				<center><h3>Nouvel utilisateur ?</h3></center>*/
/* 			</div>*/
/* 			<p>*/
/* 				<center>*/
/* 					Enregistrez-vous pour créer votre personnage et participer aux grandeur natures d'eve-oniris</p>*/
/* 				</center>*/
/* 			<p>*/
/* 				<center>*/
/* 					<a class="btn btn-primary" href="{{ path('user.register') }}">S'enregistrer</a>*/
/* 				</center>*/
/* 			</p>*/
/* 		</div>*/
/* 	</div>*/
/* </div>*/
/* */
/* {% endblock content %}*/
