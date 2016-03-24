<?php

/* admin/groupe/detail.twig */
class __TwigTemplate_5c12e95468e639c994e706a60857b09310da5109ef70ce27a0f8967bf5d76b88 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/groupe/detail.twig", 1);
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

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
\t<ol class=\"breadcrumb\">
\t\t<li><a href=\"";
        // line 6
        echo $this->env->getExtension('routing')->getPath("homepage");
        echo "\">Accueil</a></li>
\t\t";
        // line 7
        if ($this->env->getExtension('security')->isGranted("ROLE_ADMIN", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            echo "<li><a href=\"";
            echo $this->env->getExtension('routing')->getPath("groupe.admin.list");
            echo "\">Liste des groupes</a></li>";
        }
        // line 8
        echo "\t\t<li class=\"active\">Détail d'un groupe</li>
\t</ol>
\t
<div class=\"container-fluid\">
\t<div class=\"row\">
\t\t<div class=\"col-xs-12 col-md-6\">
\t\t
\t\t\t\t\t\t
\t\t\t<div class=\"well well-sm bs-component\">
\t\t\t\t<h4>
\t\t\t\t\t";
        // line 18
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : null), "numero", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : null), "numero", array()), "?")) : ("?")), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "nom", array()), "html", null, true);
        echo "
\t\t\t\t</h4>
\t\t\t</div>\t\t\t
\t\t  \t\t\t\t\t\t    \t\t
\t    \t<div class=\"list-group\">
\t    \t\t
\t    \t\t<div class=\"list-group-item\">
\t\t    \t\t<h4 class=\"list-group-item-heading\">Description <small>(Visible par tous)</small></h4>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t";
        // line 27
        if ( !$this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "description", array())) {
            // line 28
            echo "    \t\t\t\t\t<span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span>
    \t\t\t\t\tAttention, ce groupe n'a pas de description.
    \t\t\t\t";
        } else {
            // line 31
            echo "    \t\t\t\t\t";
            echo $this->env->getExtension('markdown')->markdown($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "description", array()));
            echo "
    \t\t\t\t";
        }
        // line 33
        echo "    \t\t\t\t</p>
   \t    \t\t</div>
\t\t\t\t\t\t
\t    \t\t<div class=\"list-group-item\">
\t    \t\t\t<h4 class=\"list-group-item-heading\">Territoire(s)</h4>
\t    \t\t\t
\t    \t\t\t";
        // line 39
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "territoires", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["territoire"]) {
            // line 40
            echo "\t\t    \t\t\t<p class=\"list-group-item-text\">
\t    \t\t\t\t\t";
            // line 41
            echo twig_escape_filter($this->env, $this->getAttribute($context["territoire"], "nom", array()), "html", null, true);
            echo "
\t\t\t\t\t\t</p>
    \t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['territoire'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 44
        echo "\t    \t\t</div>
\t    \t\t\t
\t    \t\t<div class=\"list-group-item\">
\t\t    \t\t<h4 class=\"list-group-item-heading\">Scénariste</h4>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t\t";
        // line 49
        if ( !$this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "scenariste", array())) {
            // line 50
            echo "    \t\t\t\t\t\t<span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span>
\t    \t\t\t\t\tAttention, ce groupe n'a pas de scénariste.
    \t\t\t\t\t";
        } else {
            // line 53
            echo "    \t\t\t\t\t\t";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "scenariste", array()), "username", array()), "html", null, true);
            echo "
    \t\t\t\t\t";
        }
        // line 55
        echo "    \t\t\t\t</p>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t\t<span class=\"glyphicon glyphicon-envelope\" aria-hidden=\"true\"></span>
    \t\t\t\t\t<a href=\"#\">Contacter par mail</a>
    \t\t\t\t</p>
\t    \t\t</div>
\t    \t\t\t
\t    \t\t<div class=\"list-group-item\">
\t    \t\t\t<h4 class=\"list-group-item-heading\">Membres du groupe</h4>
\t    \t\t\t<p class=\"list-group-item-text\">
\t    \t\t\t\t<ul>
\t    \t\t\t\t";
        // line 66
        if ($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "responsable", array())) {
            echo "<li><strong>";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "responsable", array()), "username", array()), "html", null, true);
            echo "</strong>&nbsp;(Responsable du groupe)</li>";
        }
        // line 67
        echo "\t    \t\t\t\t</ul>
\t    \t\t\t\t<table class=\"table\">
\t    \t\t\t\t\t<thead>
\t    \t\t\t\t\t\t<tr>
\t    \t\t\t\t\t\t\t<th>Utilisateur</th>
\t    \t\t\t\t\t\t\t<th>Nom prénom</th>
\t    \t\t\t\t\t\t\t<th>Email</th>
\t    \t\t\t\t\t\t\t<th>Personnage</th>
    \t\t\t\t\t\t\t\t<th></th>
\t    \t\t\t\t\t\t</tr>
\t    \t\t\t\t\t</thead>
\t    \t\t\t\t\t<tbody>
\t\t\t\t\t\t\t";
        // line 79
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "participants", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["participant"]) {
            // line 80
            echo "\t\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t\t<td>";
            // line 81
            echo twig_escape_filter($this->env, $context["participant"], "html", null, true);
            echo "</td>
\t\t\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t\t\t";
            // line 83
            if ($this->getAttribute($this->getAttribute($context["participant"], "user", array()), "etatCivil", array())) {
                // line 84
                echo "\t\t\t\t\t\t\t\t\t\t\t";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($context["participant"], "user", array()), "etatCivil", array()), "nom", array()), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($context["participant"], "user", array()), "etatCivil", array()), "prenom", array()), "html", null, true);
                echo "
\t\t\t\t\t\t\t\t\t\t";
            }
            // line 86
            echo "\t\t\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t\t\t<td>";
            // line 87
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["participant"], "user", array()), "email", array()), "html", null, true);
            echo "</td>
\t\t\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t\t\t";
            // line 89
            if ($this->getAttribute($context["participant"], "personnage", array())) {
                // line 90
                echo "\t\t\t\t\t\t\t\t\t\t\t<a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.detail", array("personnage" => $this->getAttribute($this->getAttribute($context["participant"], "personnage", array()), "id", array()))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["participant"], "personnage", array()), "publicName", array()), "html", null, true);
                echo "</a>
\t\t\t\t\t\t\t\t\t\t";
            } else {
                // line 92
                echo "\t\t\t\t\t\t\t\t\t\t\tNe dispose pas encore de personnage
\t\t\t\t\t\t\t\t\t\t";
            }
            // line 94
            echo "\t\t\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t\t\t<td><a href=\"";
            // line 95
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.admin.participant.remove", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()), "participant" => $this->getAttribute($context["participant"], "id", array()))), "html", null, true);
            echo "\">Retirer</a></td>
\t\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['participant'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 98
        echo "\t\t\t\t\t\t\t</tbody>
\t\t\t\t\t\t</table>
\t    \t\t\t</p>
\t    \t\t</div>
\t    \t\t
\t    \t\t<div class=\"list-group-item\">
\t\t    \t\t<h4 class=\"list-group-item-heading\">Trombinoscope</h4>

\t\t\t\t\t<div class=\"row\">
\t\t\t\t\t";
        // line 107
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "participants", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["participant"]) {
            // line 108
            echo "\t\t\t\t\t\t<div class=\"col-xs-6 col-md-3\">
\t\t\t\t\t\t\t";
            // line 109
            if ($this->getAttribute($this->getAttribute($context["participant"], "user", array()), "trombineUrl", array())) {
                // line 110
                echo "\t\t\t\t\t\t\t\t<img width=\"160\" src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("trombine.get", array("trombine" => $this->getAttribute($this->getAttribute($context["participant"], "user", array()), "trombineUrl", array()))), "html", null, true);
                echo "\" />
\t\t\t\t\t\t\t\t";
                // line 111
                if ($this->getAttribute($context["participant"], "personnage", array())) {
                    // line 112
                    echo "\t\t\t\t\t\t\t\t\t<div>";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["participant"], "personnage", array()), "publicName", array()), "html", null, true);
                    echo "</div>
\t\t\t\t\t\t\t\t";
                }
                // line 114
                echo "\t\t\t\t\t\t\t\t<div>";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($context["participant"], "user", array()), "etatCivil", array()), "nom", array()), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($context["participant"], "user", array()), "etatCivil", array()), "prenom", array()), "html", null, true);
                echo "</div>
\t\t\t\t\t\t\t";
            }
            // line 116
            echo "\t\t\t\t\t\t</div>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['participant'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 118
        echo "\t\t\t\t\t</div>
\t\t\t\t</div>
\t    \t\t\t
\t    \t\t<div class=\"list-group-item\">
\t\t    \t\t<h4 class=\"list-group-item-heading\">Code</h4>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t\t<strong>";
        // line 124
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "code", array()), "html", null, true);
        echo "</strong>
    \t\t\t\t</p>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t\tCe code permet à des joueurs invité par le chef de groupe de participer au groupe.
    \t\t\t\t</p>
\t    \t\t</div>
\t    \t\t\t
\t    \t\t<div class=\"list-group-item\">
\t\t    \t\t<h4 class=\"list-group-item-heading\">Jeu stratégique</h4>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t";
        // line 134
        if ($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "jeuStrategique", array())) {
            // line 135
            echo "    \t\t\t\t\t<span class=\"glyphicon glyphicon-ok-circle\" aria-hidden=\"true\"></span>
\t\t    \t\t\tparticipe au jeu stratégique.
\t\t    \t\t";
        } else {
            // line 138
            echo "    \t\t\t\t\tne participe pas au jeu stratégique.
    \t\t\t\t";
        }
        // line 140
        echo "\t\t    \t\t</p>
\t    \t\t</div>
\t    \t\t\t
\t    \t\t<div class=\"list-group-item\">
    \t\t\t\t<h4 class=\"list-group-item-heading\">Jeu maritime</h4>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t";
        // line 146
        if ($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "jeuMaritime", array())) {
            // line 147
            echo "    \t\t\t\t\t<span class=\"glyphicon glyphicon-ok-circle\" aria-hidden=\"true\"></span>
\t\t    \t\t\tparticipe au jeu maritime.
\t\t    \t\t";
        } else {
            // line 150
            echo "    \t\t\t\t\tne participe pas au jeu maritime.
    \t\t\t\t";
        }
        // line 152
        echo "\t\t    \t\t</p>
\t\t    \t</div>
\t\t    \t\t
\t\t    \t<div class=\"list-group-item\">
    \t\t\t\t<h4 class=\"list-group-item-heading\">Composition du groupe</h4>
    \t\t\t\t<p class=\"list-group-item-text\"><strong>Nombre de place ouverte : </strong>";
        // line 157
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "classeOpen", array()), "html", null, true);
        echo "</p>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t\t";
        // line 159
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "classes", array())) == 0)) {
            // line 160
            echo "    \t\t\t\t\t<span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span>
\t    \t\t\t\t\tAttention, ce groupe n'a pas de classes définies.
    \t\t\t\t\t";
        } else {
            // line 163
            echo "    \t\t\t\t\t\t";
            echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "classes", array()), ", "), "html", null, true);
            echo "
    \t\t\t\t\t";
        }
        // line 165
        echo "    \t\t\t\t</p>
    \t\t\t</div>
    \t\t\t
    \t\t\t<div class=\"list-group-item\">
    \t\t\t\t<h4 class=\"list-group-item-heading\">Alliés</h4>
\t\t\t\t\t";
        // line 170
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "alliances", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["alliance"]) {
            // line 171
            echo "\t    \t\t\t\t<p class=\"list-group-item-text\">
\t    \t\t\t\t\t";
            // line 172
            if (($this->getAttribute($context["alliance"], "groupe", array()) == (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")))) {
                // line 173
                echo "\t\t\t\t\t\t\t\t";
                echo twig_escape_filter($this->env, $this->getAttribute($context["alliance"], "requestedGroupe", array()), "html", null, true);
                echo "
\t\t\t\t\t\t\t";
            } else {
                // line 175
                echo "\t\t\t\t\t\t\t\t";
                echo twig_escape_filter($this->env, $this->getAttribute($context["alliance"], "groupe", array()), "html", null, true);
                echo "
\t\t\t\t\t\t\t";
            }
            // line 177
            echo "\t    \t\t\t\t</p>
    \t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['alliance'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 179
        echo "    \t\t\t\t";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "waitingAlliances", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["alliance"]) {
            // line 180
            echo "\t    \t\t\t\t<p class=\"list-group-item-text\">
\t    \t\t\t\t\t";
            // line 181
            if (($this->getAttribute($context["alliance"], "groupe", array()) == (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")))) {
                // line 182
                echo "\t    \t\t\t\t\t\tA demandé une alliance avec <strong>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["alliance"], "requestedGroupe", array()), "html", null, true);
                echo "</strong>.
\t    \t\t\t\t\t";
            } else {
                // line 184
                echo "\t    \t\t\t\t\t\tSollicité pour une alliance par <strong>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["alliance"], "groupe", array()), "html", null, true);
                echo "</strong>.
\t    \t\t\t\t\t";
            }
            // line 186
            echo "\t    \t\t\t\t</p>
    \t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['alliance'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 188
        echo "    \t\t\t</div>
    \t\t\t
    \t\t\t<div class=\"list-group-item\">
    \t\t\t\t<h4 class=\"list-group-item-heading\">Ennemis</h4>
    \t\t\t\t";
        // line 192
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "ennemies", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["ennemi"]) {
            // line 193
            echo "    \t\t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t\t\t";
            // line 194
            if (($this->getAttribute($context["ennemi"], "groupe", array()) == (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")))) {
                // line 195
                echo "\t\t\t\t\t\t\t\tA avez déclaré la guerre à <strong>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["ennemi"], "requestedGroupe", array()), "html", null, true);
                echo "</strong>.
\t\t\t\t\t\t\t";
            } else {
                // line 197
                echo "\t\t\t\t\t\t\t\t<strong>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["ennemi"], "groupe", array()), "html", null, true);
                echo "</strong> leur a déclaré la guerre.
\t\t\t\t\t\t\t";
            }
            // line 199
            echo "    \t\t\t\t\t</p>
    \t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['ennemi'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 201
        echo "    \t\t\t\t";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "waitingPeace", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["war"]) {
            // line 202
            echo "    \t\t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t\t\t";
            // line 203
            if (($this->getAttribute($context["war"], "groupe", array()) == (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")))) {
                // line 204
                echo "    \t\t\t\t\t\t\tA avez demandé la paix avec <strong>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["war"], "requestedGroupe", array()), "html", null, true);
                echo "</strong>.
    \t\t\t\t\t\t";
            } else {
                // line 206
                echo "    \t\t\t\t\t\t\t<strong>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["war"], "groupe", array()), "html", null, true);
                echo "</strong> propose la paix.
    \t\t\t\t\t\t";
            }
            // line 208
            echo "    \t\t\t\t\t</p>
    \t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['war'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 210
        echo "    \t\t\t</div>

    \t\t\t<div class=\"list-group-item\">
    \t\t\t\t<h4 class=\"list-group-item-heading\">Gns</h4>
\t\t\t\t\t
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t";
        // line 216
        echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "gns", array()), ", "), "html", null, true);
        echo "
\t\t\t\t\t</p>
\t\t\t\t</div>
    \t\t\t\t    \t\t\t\t

\t  \t\t\t<div class=\"list-group-item\">
\t\t  \t\t\t<div class=\"btn-group\" role=\"group\" aria-label=\"...\">
\t\t  \t\t\t\t<a  class=\"btn btn-primary\" role=\"button\" href=\"";
        // line 223
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.update", array("index" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()))), "html", null, true);
        echo "\">Modifier</a>
\t\t\t\t\t</div>
\t\t\t\t</div>
  \t\t\t</div>
\t\t</div>
\t\t
\t\t<div class=\"col-xs-12 col-md-6\">
\t\t
\t\t\t<div class=\"panel panel-default\">
\t\t\t\t<div class=\"panel-heading\">Background</div>
\t\t\t\t<div class=\"panel-body\">
\t\t\t\t\t<a href=\"";
        // line 234
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("background.add", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()))), "html", null, true);
        echo "\">Ajouter un élément de background</a>
\t\t\t\t</div>
\t\t\t\t<div class=\"list-group\">
\t\t\t\t\t";
        // line 237
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "backgrounds", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["background"]) {
            // line 238
            echo "\t\t\t\t\t\t<div class=\"list-group-item\">
\t\t\t    \t\t\t
\t\t\t    \t\t\t<p class=\"list-group-item-text text-warning\">
\t\t\t    \t\t\t\t";
            // line 241
            echo twig_escape_filter($this->env, $this->env->getExtension('larpmanager_extension')->explainVisibility($this->getAttribute($context["background"], "visibility", array())), "html", null, true);
            echo "
\t\t\t    \t\t\t</p>
\t\t\t    \t\t\t<p class=\"list-group-item-text\">
\t\t\t    \t\t\t\t";
            // line 244
            echo $this->env->getExtension('markdown')->markdown($this->getAttribute($context["background"], "text", array()));
            echo "
\t\t\t    \t\t\t</p>
\t\t\t    \t\t\t<p class=\"list-group-item-text\">
\t\t    \t\t\t\t\t<a href=\"";
            // line 247
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("background.update", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()), "background" => $this->getAttribute($context["background"], "id", array()), "groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()))), "html", null, true);
            echo "\">Modifier le background</a>
\t\t    \t\t\t\t</p>
\t\t\t    \t\t</div>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['background'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 251
        echo "\t\t\t\t</div>\t
\t\t\t</div>
\t\t</div>
\t</div>
</div>
\t
\t\t\t
";
    }

    public function getTemplateName()
    {
        return "admin/groupe/detail.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  537 => 251,  527 => 247,  521 => 244,  515 => 241,  510 => 238,  506 => 237,  500 => 234,  486 => 223,  476 => 216,  468 => 210,  461 => 208,  455 => 206,  449 => 204,  447 => 203,  444 => 202,  439 => 201,  432 => 199,  426 => 197,  420 => 195,  418 => 194,  415 => 193,  411 => 192,  405 => 188,  398 => 186,  392 => 184,  386 => 182,  384 => 181,  381 => 180,  376 => 179,  369 => 177,  363 => 175,  357 => 173,  355 => 172,  352 => 171,  348 => 170,  341 => 165,  335 => 163,  330 => 160,  328 => 159,  323 => 157,  316 => 152,  312 => 150,  307 => 147,  305 => 146,  297 => 140,  293 => 138,  288 => 135,  286 => 134,  273 => 124,  265 => 118,  258 => 116,  250 => 114,  244 => 112,  242 => 111,  237 => 110,  235 => 109,  232 => 108,  228 => 107,  217 => 98,  208 => 95,  205 => 94,  201 => 92,  193 => 90,  191 => 89,  186 => 87,  183 => 86,  175 => 84,  173 => 83,  168 => 81,  165 => 80,  161 => 79,  147 => 67,  141 => 66,  128 => 55,  122 => 53,  117 => 50,  115 => 49,  108 => 44,  99 => 41,  96 => 40,  92 => 39,  84 => 33,  78 => 31,  73 => 28,  71 => 27,  57 => 18,  45 => 8,  39 => 7,  35 => 6,  31 => 4,  28 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block content %}*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage') }}">Accueil</a></li>*/
/* 		{% if is_granted('ROLE_ADMIN', app.user) %}<li><a href="{{ path("groupe.admin.list") }}">Liste des groupes</a></li>{% endif %}*/
/* 		<li class="active">Détail d'un groupe</li>*/
/* 	</ol>*/
/* 	*/
/* <div class="container-fluid">*/
/* 	<div class="row">*/
/* 		<div class="col-xs-12 col-md-6">*/
/* 		*/
/* 						*/
/* 			<div class="well well-sm bs-component">*/
/* 				<h4>*/
/* 					{{ groupe.numero|default('?') }} - {{ groupe.nom }}*/
/* 				</h4>*/
/* 			</div>			*/
/* 		  						    		*/
/* 	    	<div class="list-group">*/
/* 	    		*/
/* 	    		<div class="list-group-item">*/
/* 		    		<h4 class="list-group-item-heading">Description <small>(Visible par tous)</small></h4>*/
/*     				<p class="list-group-item-text">*/
/*     				{% if not groupe.description %}*/
/*     					<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>*/
/*     					Attention, ce groupe n'a pas de description.*/
/*     				{% else %}*/
/*     					{{ groupe.description|markdown }}*/
/*     				{% endif %}*/
/*     				</p>*/
/*    	    		</div>*/
/* 						*/
/* 	    		<div class="list-group-item">*/
/* 	    			<h4 class="list-group-item-heading">Territoire(s)</h4>*/
/* 	    			*/
/* 	    			{% for territoire in groupe.territoires %}*/
/* 		    			<p class="list-group-item-text">*/
/* 	    					{{ territoire.nom }}*/
/* 						</p>*/
/*     				{% endfor %}*/
/* 	    		</div>*/
/* 	    			*/
/* 	    		<div class="list-group-item">*/
/* 		    		<h4 class="list-group-item-heading">Scénariste</h4>*/
/*     				<p class="list-group-item-text">*/
/*     					{% if not groupe.scenariste %}*/
/*     						<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>*/
/* 	    					Attention, ce groupe n'a pas de scénariste.*/
/*     					{% else %}*/
/*     						{{ groupe.scenariste.username }}*/
/*     					{% endif %}*/
/*     				</p>*/
/*     				<p class="list-group-item-text">*/
/*     					<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>*/
/*     					<a href="#">Contacter par mail</a>*/
/*     				</p>*/
/* 	    		</div>*/
/* 	    			*/
/* 	    		<div class="list-group-item">*/
/* 	    			<h4 class="list-group-item-heading">Membres du groupe</h4>*/
/* 	    			<p class="list-group-item-text">*/
/* 	    				<ul>*/
/* 	    				{% if groupe.responsable %}<li><strong>{{ groupe.responsable.username }}</strong>&nbsp;(Responsable du groupe)</li>{% endif %}*/
/* 	    				</ul>*/
/* 	    				<table class="table">*/
/* 	    					<thead>*/
/* 	    						<tr>*/
/* 	    							<th>Utilisateur</th>*/
/* 	    							<th>Nom prénom</th>*/
/* 	    							<th>Email</th>*/
/* 	    							<th>Personnage</th>*/
/*     								<th></th>*/
/* 	    						</tr>*/
/* 	    					</thead>*/
/* 	    					<tbody>*/
/* 							{% for participant in groupe.participants %}*/
/* 								<tr>*/
/* 									<td>{{ participant }}</td>*/
/* 									<td>*/
/* 										{% if participant.user.etatCivil %}*/
/* 											{{ participant.user.etatCivil.nom }} {{ participant.user.etatCivil.prenom }}*/
/* 										{% endif %}*/
/* 									</td>*/
/* 									<td>{{ participant.user.email }}</td>*/
/* 									<td>*/
/* 										{% if participant.personnage %}*/
/* 											<a href="{{ path('personnage.admin.detail', {'personnage': participant.personnage.id}) }}">{{ participant.personnage.publicName }}</a>*/
/* 										{% else %}*/
/* 											Ne dispose pas encore de personnage*/
/* 										{% endif %}*/
/* 									</td>*/
/* 									<td><a href="{{ path('groupe.admin.participant.remove', {'groupe': groupe.id, 'participant': participant.id}) }}">Retirer</a></td>*/
/* 								</tr>*/
/* 							{% endfor %}*/
/* 							</tbody>*/
/* 						</table>*/
/* 	    			</p>*/
/* 	    		</div>*/
/* 	    		*/
/* 	    		<div class="list-group-item">*/
/* 		    		<h4 class="list-group-item-heading">Trombinoscope</h4>*/
/* */
/* 					<div class="row">*/
/* 					{% for participant in  groupe.participants %}*/
/* 						<div class="col-xs-6 col-md-3">*/
/* 							{% if participant.user.trombineUrl %}*/
/* 								<img width="160" src="{{ path('trombine.get', {'trombine' : participant.user.trombineUrl }) }}" />*/
/* 								{% if participant.personnage %}*/
/* 									<div>{{ participant.personnage.publicName }}</div>*/
/* 								{% endif %}*/
/* 								<div>{{ participant.user.etatCivil.nom }} {{ participant.user.etatCivil.prenom }}</div>*/
/* 							{% endif %}*/
/* 						</div>*/
/* 					{% endfor %}*/
/* 					</div>*/
/* 				</div>*/
/* 	    			*/
/* 	    		<div class="list-group-item">*/
/* 		    		<h4 class="list-group-item-heading">Code</h4>*/
/*     				<p class="list-group-item-text">*/
/*     					<strong>{{ groupe.code }}</strong>*/
/*     				</p>*/
/*     				<p class="list-group-item-text">*/
/*     					Ce code permet à des joueurs invité par le chef de groupe de participer au groupe.*/
/*     				</p>*/
/* 	    		</div>*/
/* 	    			*/
/* 	    		<div class="list-group-item">*/
/* 		    		<h4 class="list-group-item-heading">Jeu stratégique</h4>*/
/*     				<p class="list-group-item-text">*/
/*     				{% if groupe.jeuStrategique %}*/
/*     					<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>*/
/* 		    			participe au jeu stratégique.*/
/* 		    		{% else %}*/
/*     					ne participe pas au jeu stratégique.*/
/*     				{% endif %}*/
/* 		    		</p>*/
/* 	    		</div>*/
/* 	    			*/
/* 	    		<div class="list-group-item">*/
/*     				<h4 class="list-group-item-heading">Jeu maritime</h4>*/
/*     				<p class="list-group-item-text">*/
/*     				{% if groupe.jeuMaritime %}*/
/*     					<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>*/
/* 		    			participe au jeu maritime.*/
/* 		    		{% else %}*/
/*     					ne participe pas au jeu maritime.*/
/*     				{% endif %}*/
/* 		    		</p>*/
/* 		    	</div>*/
/* 		    		*/
/* 		    	<div class="list-group-item">*/
/*     				<h4 class="list-group-item-heading">Composition du groupe</h4>*/
/*     				<p class="list-group-item-text"><strong>Nombre de place ouverte : </strong>{{ groupe.classeOpen }}</p>*/
/*     				<p class="list-group-item-text">*/
/*     					{% if groupe.classes|length == 0 %}*/
/*     					<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>*/
/* 	    					Attention, ce groupe n'a pas de classes définies.*/
/*     					{% else %}*/
/*     						{{ groupe.classes|join(', ') }}*/
/*     					{% endif %}*/
/*     				</p>*/
/*     			</div>*/
/*     			*/
/*     			<div class="list-group-item">*/
/*     				<h4 class="list-group-item-heading">Alliés</h4>*/
/* 					{% for alliance in groupe.alliances %}*/
/* 	    				<p class="list-group-item-text">*/
/* 	    					{% if alliance.groupe == groupe %}*/
/* 								{{ alliance.requestedGroupe }}*/
/* 							{% else %}*/
/* 								{{ alliance.groupe }}*/
/* 							{% endif %}*/
/* 	    				</p>*/
/*     				{% endfor %}*/
/*     				{% for alliance in groupe.waitingAlliances %}*/
/* 	    				<p class="list-group-item-text">*/
/* 	    					{% if alliance.groupe == groupe %}*/
/* 	    						A demandé une alliance avec <strong>{{ alliance.requestedGroupe }}</strong>.*/
/* 	    					{% else %}*/
/* 	    						Sollicité pour une alliance par <strong>{{ alliance.groupe }}</strong>.*/
/* 	    					{% endif %}*/
/* 	    				</p>*/
/*     				{% endfor %}*/
/*     			</div>*/
/*     			*/
/*     			<div class="list-group-item">*/
/*     				<h4 class="list-group-item-heading">Ennemis</h4>*/
/*     				{% for ennemi in groupe.ennemies %}*/
/*     					<p class="list-group-item-text">*/
/*     						{% if ennemi.groupe == groupe %}*/
/* 								A avez déclaré la guerre à <strong>{{ ennemi.requestedGroupe }}</strong>.*/
/* 							{% else %}*/
/* 								<strong>{{ ennemi.groupe }}</strong> leur a déclaré la guerre.*/
/* 							{% endif %}*/
/*     					</p>*/
/*     				{% endfor %}*/
/*     				{% for war in groupe.waitingPeace %}*/
/*     					<p class="list-group-item-text">*/
/*     						{% if war.groupe == groupe %}*/
/*     							A avez demandé la paix avec <strong>{{ war.requestedGroupe }}</strong>.*/
/*     						{% else %}*/
/*     							<strong>{{ war.groupe }}</strong> propose la paix.*/
/*     						{% endif %}*/
/*     					</p>*/
/*     				{% endfor %}*/
/*     			</div>*/
/* */
/*     			<div class="list-group-item">*/
/*     				<h4 class="list-group-item-heading">Gns</h4>*/
/* 					*/
/* 					<p class="list-group-item-text">*/
/* 						{{ groupe.gns|join(', ') }}*/
/* 					</p>*/
/* 				</div>*/
/*     				    				*/
/* */
/* 	  			<div class="list-group-item">*/
/* 		  			<div class="btn-group" role="group" aria-label="...">*/
/* 		  				<a  class="btn btn-primary" role="button" href="{{ path('groupe.update', {index : groupe.id}) }}">Modifier</a>*/
/* 					</div>*/
/* 				</div>*/
/*   			</div>*/
/* 		</div>*/
/* 		*/
/* 		<div class="col-xs-12 col-md-6">*/
/* 		*/
/* 			<div class="panel panel-default">*/
/* 				<div class="panel-heading">Background</div>*/
/* 				<div class="panel-body">*/
/* 					<a href="{{ path('background.add', {'groupe': groupe.id}) }}">Ajouter un élément de background</a>*/
/* 				</div>*/
/* 				<div class="list-group">*/
/* 					{% for background in groupe.backgrounds %}*/
/* 						<div class="list-group-item">*/
/* 			    			*/
/* 			    			<p class="list-group-item-text text-warning">*/
/* 			    				{{ background.visibility|explainVisibility }}*/
/* 			    			</p>*/
/* 			    			<p class="list-group-item-text">*/
/* 			    				{{ background.text|markdown }}*/
/* 			    			</p>*/
/* 			    			<p class="list-group-item-text">*/
/* 		    					<a href="{{ path('background.update', {'groupe': groupe.id, 'background': background.id, 'groupe': groupe.id}) }}">Modifier le background</a>*/
/* 		    				</p>*/
/* 			    		</div>*/
/* 					{% endfor %}*/
/* 				</div>	*/
/* 			</div>*/
/* 		</div>*/
/* 	</div>*/
/* </div>*/
/* 	*/
/* 			*/
/* {% endblock %}*/
/* */
