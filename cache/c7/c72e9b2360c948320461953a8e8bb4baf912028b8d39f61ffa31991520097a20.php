<?php

/* classe/fragment/info.twig */
class __TwigTemplate_65ea2f286848d64bf93c119ab6ab1589dea8ac30017204fb6781c996234630c6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"panel panel-default\">

\t<div class=\"header\">
\t\t<h5>";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["classe"]) ? $context["classe"] : $this->getContext($context, "classe")), "label", array()), "html", null, true);
        echo "</h5>
\t</div>
\t
\t<div class=\"panel-body\">
\t\t<blockquote>
\t\t\t<p class=\"text-justify\">";
        // line 9
        echo $this->env->getExtension('markdown')->markdown($this->getAttribute((isset($context["classe"]) ? $context["classe"] : $this->getContext($context, "classe")), "description", array()));
        echo "</p>
\t\t</blockquote>
\t\t<div class=\"row\">
\t\t\t<div class=\"col-lg-3\">
\t\t\t\t<img width=\"184\" height=\"250\" alt=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["classe"]) ? $context["classe"] : $this->getContext($context, "classe")), "labelFeminin", array()), "html", null, true);
        echo "\" src=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/img/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["classe"]) ? $context["classe"] : $this->getContext($context, "classe")), "imageF", array()), "html", null, true);
        echo "\" />
\t\t\t</div>
\t\t\t<div class=\"col-lg-6\">
\t\t\t\t<h6>
\t\t\t\t\t<p class=\"text-center\">Compétences acquises à la création</p>
\t\t\t\t</h6>
\t\t\t\t
\t\t\t\t<p>
\t\t      \t\t";
        // line 21
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["classe"]) ? $context["classe"] : $this->getContext($context, "classe")), "competenceFamilyCreations", array()));
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
        foreach ($context['_seq'] as $context["_key"] => $context["competenceFamily"]) {
            // line 22
            echo "\t\t\t\t\t\t";
            if (($this->getAttribute($context["loop"], "index", array()) != 1)) {
                echo "&nbsp;<i class=\"fa fa-ellipsis-h\"></i>&nbsp;";
            }
            // line 23
            echo "\t      \t\t\t\t<span data-toggle=\"tooltip\" data-placement=\"top\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["competenceFamily"], "descriptionRaw", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["competenceFamily"], "label", array()), "html", null, true);
            echo "</span>
\t      \t\t\t";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['competenceFamily'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "\t\t      \t</p>
\t\t\t
\t\t\t\t<h6>
\t\t\t\t\t<p class=\"text-center\">Compétences favorites</p>
\t\t\t\t</h6>
\t\t\t\t
\t\t      \t<p>
\t\t\t\t\t";
        // line 32
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["classe"]) ? $context["classe"] : $this->getContext($context, "classe")), "competenceFamilyFavorites", array()));
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
        foreach ($context['_seq'] as $context["_key"] => $context["competenceFamily"]) {
            // line 33
            echo "\t\t\t\t\t\t";
            if (($this->getAttribute($context["loop"], "index", array()) != 1)) {
                echo "&nbsp;<i class=\"fa fa-ellipsis-h\"></i>&nbsp;";
            }
            // line 34
            echo "\t      \t\t\t\t<span data-toggle=\"tooltip\" data-placement=\"top\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["competenceFamily"], "descriptionRaw", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["competenceFamily"], "label", array()), "html", null, true);
            echo "</span>
\t      \t\t\t";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['competenceFamily'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 36
        echo "\t\t      \t</p>
\t\t      \t
\t\t\t\t<h6>
\t\t\t\t\t<p class=\"text-center\">Compétences normales</p>
\t\t\t\t</h6>
\t\t\t\t
\t\t      \t<p>
\t\t\t\t\t";
        // line 43
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["classe"]) ? $context["classe"] : $this->getContext($context, "classe")), "competenceFamilyNormales", array()));
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
        foreach ($context['_seq'] as $context["_key"] => $context["competenceFamily"]) {
            // line 44
            echo "\t\t\t\t\t\t";
            if (($this->getAttribute($context["loop"], "index", array()) != 1)) {
                echo "&nbsp;<i class=\"fa fa-ellipsis-h\"></i>&nbsp;";
            }
            // line 45
            echo "\t      \t\t\t\t<span  data-toggle=\"tooltip\" data-placement=\"top\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["competenceFamily"], "descriptionRaw", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["competenceFamily"], "label", array()), "html", null, true);
            echo "</span>
\t      \t\t\t";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['competenceFamily'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 47
        echo "\t\t\t    </p>
\t      \t\t
\t\t\t</div>
\t\t\t<div class=\"col-lg-3\">
\t\t\t\t<img width=\"184\" height=\"250\" alt=\"";
        // line 51
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["classe"]) ? $context["classe"] : $this->getContext($context, "classe")), "labelMasculin", array()), "html", null, true);
        echo "\" src=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/img/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["classe"]) ? $context["classe"] : $this->getContext($context, "classe")), "imageM", array()), "html", null, true);
        echo "\" />
\t\t\t</div>
\t\t</div>
\t</div>
</div>";
    }

    public function getTemplateName()
    {
        return "classe/fragment/info.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  201 => 51,  195 => 47,  176 => 45,  171 => 44,  154 => 43,  145 => 36,  126 => 34,  121 => 33,  104 => 32,  95 => 25,  76 => 23,  71 => 22,  54 => 21,  39 => 13,  32 => 9,  24 => 4,  19 => 1,);
    }
}
/* <div class="panel panel-default">*/
/* */
/* 	<div class="header">*/
/* 		<h5>{{ classe.label }}</h5>*/
/* 	</div>*/
/* 	*/
/* 	<div class="panel-body">*/
/* 		<blockquote>*/
/* 			<p class="text-justify">{{ classe.description|markdown }}</p>*/
/* 		</blockquote>*/
/* 		<div class="row">*/
/* 			<div class="col-lg-3">*/
/* 				<img width="184" height="250" alt="{{ classe.labelFeminin }}" src="{{ app.request.basepath }}/img/{{ classe.imageF }}" />*/
/* 			</div>*/
/* 			<div class="col-lg-6">*/
/* 				<h6>*/
/* 					<p class="text-center">Compétences acquises à la création</p>*/
/* 				</h6>*/
/* 				*/
/* 				<p>*/
/* 		      		{% for competenceFamily in classe.competenceFamilyCreations %}*/
/* 						{% if loop.index != 1 %}&nbsp;<i class="fa fa-ellipsis-h"></i>&nbsp;{% endif %}*/
/* 	      				<span data-toggle="tooltip" data-placement="top" title="{{ competenceFamily.descriptionRaw }}">{{ competenceFamily.label }}</span>*/
/* 	      			{% endfor %}*/
/* 		      	</p>*/
/* 			*/
/* 				<h6>*/
/* 					<p class="text-center">Compétences favorites</p>*/
/* 				</h6>*/
/* 				*/
/* 		      	<p>*/
/* 					{% for competenceFamily in classe.competenceFamilyFavorites %}*/
/* 						{% if loop.index != 1 %}&nbsp;<i class="fa fa-ellipsis-h"></i>&nbsp;{% endif %}*/
/* 	      				<span data-toggle="tooltip" data-placement="top" title="{{ competenceFamily.descriptionRaw }}">{{ competenceFamily.label }}</span>*/
/* 	      			{% endfor %}*/
/* 		      	</p>*/
/* 		      	*/
/* 				<h6>*/
/* 					<p class="text-center">Compétences normales</p>*/
/* 				</h6>*/
/* 				*/
/* 		      	<p>*/
/* 					{% for competenceFamily in classe.competenceFamilyNormales %}*/
/* 						{% if loop.index != 1 %}&nbsp;<i class="fa fa-ellipsis-h"></i>&nbsp;{% endif %}*/
/* 	      				<span  data-toggle="tooltip" data-placement="top" title="{{ competenceFamily.descriptionRaw }}">{{ competenceFamily.label }}</span>*/
/* 	      			{% endfor %}*/
/* 			    </p>*/
/* 	      		*/
/* 			</div>*/
/* 			<div class="col-lg-3">*/
/* 				<img width="184" height="250" alt="{{ classe.labelMasculin }}" src="{{ app.request.basepath }}/img/{{ classe.imageM }}" />*/
/* 			</div>*/
/* 		</div>*/
/* 	</div>*/
/* </div>*/
