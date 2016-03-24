<?php

/* user/login.twig */
class __TwigTemplate_496b872738f9dccc3e7a18f9b72695b0c70e2a85a75b8642aac52426a4d4a77e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "user/login.twig", 1);
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
        echo "Sign in";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
    ";
        // line 7
        if ($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array())) {
            // line 8
            echo "        <p>Hello, ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "displayName", array()), "html", null, true);
            echo ".</p>
        <p><a href=\"";
            // line 9
            echo $this->env->getExtension('routing')->getPath("user.logout");
            echo "\">Sign out</a></p>
    ";
        } else {
            // line 11
            echo "\t\t<div class=\"well\">
\t        <p class=\"text-muted lead\">
\t            Vous n'avez pas de compte ? <a href=\"";
            // line 13
            echo $this->env->getExtension('routing')->getPath("user.register");
            echo "\">Enregistrez-vous.</a>
\t        </p>
\t
\t        ";
            // line 16
            if ((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error"))) {
                // line 17
                echo "\t            <div class=\"alert alert-danger\">";
                echo nl2br(twig_escape_filter($this->env, (isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "html", null, true));
                echo "</div>
\t        ";
            }
            // line 19
            echo "\t
\t        <form class=\"form-horizontal\" method=\"POST\" action=\"";
            // line 20
            echo $this->env->getExtension('routing')->getPath("user.login_check");
            echo "\">
\t
\t            <div class=\"form-group\">
\t                <label class=\"col-sm-2 control-label\" for=\"inputEmail\">Email</label>
\t                <div class=\"col-sm-6\">
\t                    <input class=\"form-control\" name=\"_username\" type=\"text\" id=\"inputEmail\" placeholder=\"Email\" required value=\"";
            // line 25
            echo twig_escape_filter($this->env, (isset($context["last_username"]) ? $context["last_username"] : $this->getContext($context, "last_username")), "html", null, true);
            echo "\">
\t                </div>
\t            </div>
\t
\t            <div class=\"form-group\">
\t                <label class=\"col-sm-2 control-label\" for=\"inputPassword\">Mot de passe</label>
\t                <div class=\"col-sm-6\">
\t                    <input class=\"form-control\" name=\"_password\" type=\"password\" id=\"inputPassword\" required placeholder=\"Mot de passe\">
\t                </div>
\t            </div>
\t
\t            ";
            // line 36
            if ((isset($context["allowRememberMe"]) ? $context["allowRememberMe"] : $this->getContext($context, "allowRememberMe"))) {
                // line 37
                echo "\t                <div class=\"form-group\">
\t                    <div class=\"col-sm-6 col-sm-offset-2 checkbox\">
\t                        <label>
\t                            <input type=\"checkbox\" name=\"_remember_me\" value=\"true\" checked> Se rappeler de moi sur cet ordinateur
\t                        </label>
\t                    </div>
\t                </div>
\t            ";
            }
            // line 45
            echo "\t
\t            <div class=\"form-group\">
\t                <div class=\"col-sm-8 col-sm-offset-2\">
\t                    <button type=\"submit\" class=\"btn btn-primary\">Se connecter</button>
\t                    <a style=\"margin-left: 10px;\" href=\"";
            // line 49
            echo $this->env->getExtension('routing')->getPath("user.forgot-password");
            echo "\">Mot de passe oublié ?</a>
\t                </div>
\t            </div>
\t
\t        </form>
\t       </div>

    ";
        }
    }

    public function getTemplateName()
    {
        return "user/login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  114 => 49,  108 => 45,  98 => 37,  96 => 36,  82 => 25,  74 => 20,  71 => 19,  65 => 17,  63 => 16,  57 => 13,  53 => 11,  48 => 9,  43 => 8,  41 => 7,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig"  %}*/
/* */
/* {% block title %}Sign in{% endblock %}*/
/* */
/* {% block content %}*/
/* */
/*     {% if app.user %}*/
/*         <p>Hello, {{ app.user.displayName }}.</p>*/
/*         <p><a href="{{ path('user.logout') }}">Sign out</a></p>*/
/*     {% else %}*/
/* 		<div class="well">*/
/* 	        <p class="text-muted lead">*/
/* 	            Vous n'avez pas de compte ? <a href="{{ path('user.register') }}">Enregistrez-vous.</a>*/
/* 	        </p>*/
/* 	*/
/* 	        {% if error %}*/
/* 	            <div class="alert alert-danger">{{ error|nl2br }}</div>*/
/* 	        {% endif %}*/
/* 	*/
/* 	        <form class="form-horizontal" method="POST" action="{{ path('user.login_check') }}">*/
/* 	*/
/* 	            <div class="form-group">*/
/* 	                <label class="col-sm-2 control-label" for="inputEmail">Email</label>*/
/* 	                <div class="col-sm-6">*/
/* 	                    <input class="form-control" name="_username" type="text" id="inputEmail" placeholder="Email" required value="{{ last_username }}">*/
/* 	                </div>*/
/* 	            </div>*/
/* 	*/
/* 	            <div class="form-group">*/
/* 	                <label class="col-sm-2 control-label" for="inputPassword">Mot de passe</label>*/
/* 	                <div class="col-sm-6">*/
/* 	                    <input class="form-control" name="_password" type="password" id="inputPassword" required placeholder="Mot de passe">*/
/* 	                </div>*/
/* 	            </div>*/
/* 	*/
/* 	            {% if allowRememberMe %}*/
/* 	                <div class="form-group">*/
/* 	                    <div class="col-sm-6 col-sm-offset-2 checkbox">*/
/* 	                        <label>*/
/* 	                            <input type="checkbox" name="_remember_me" value="true" checked> Se rappeler de moi sur cet ordinateur*/
/* 	                        </label>*/
/* 	                    </div>*/
/* 	                </div>*/
/* 	            {% endif %}*/
/* 	*/
/* 	            <div class="form-group">*/
/* 	                <div class="col-sm-8 col-sm-offset-2">*/
/* 	                    <button type="submit" class="btn btn-primary">Se connecter</button>*/
/* 	                    <a style="margin-left: 10px;" href="{{ path('user.forgot-password') }}">Mot de passe oublié ?</a>*/
/* 	                </div>*/
/* 	            </div>*/
/* 	*/
/* 	        </form>*/
/* 	       </div>*/
/* */
/*     {% endif %}*/
/* {% endblock %}*/
