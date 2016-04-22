<?php

/* public/groupe/fragment/groupe.twig */
class __TwigTemplate_9fe4d4f81e7e46b8f08fa665bae5060078b1fedff59a37b93c8590780588cfe6 extends Twig_Template
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
        // line 2
        echo "
<h4>";
        // line 3
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "nom", array()), "html", null, true);
        echo "</h4>

";
        // line 5
        if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "isResponsable", array(0 => (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe"))), "method")) {
            // line 6
            echo "<blockquote>
Vous êtes responsable de ce groupe.
</blockquote>
";
        }
        // line 10
        echo "
<p>";
        // line 11
        echo $this->env->getExtension('markdown')->markdown($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "description", array()));
        echo "</p>

<div class=\"header\">
\t<h5>Composition</h5>
</div>

<ul class=\"list-group\">
\t";
        // line 18
        if ($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "responsable", array())) {
            // line 19
            echo "\t\t<li class=\"list-group-item\"><strong>Responsable : </strong>";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "responsable", array()), "username", array()), "html", null, true);
            echo " / ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "responsable", array()), "email", array()), "html", null, true);
            echo "</li>
\t";
        }
        // line 21
        echo "\t";
        if ($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "scenariste", array())) {
            // line 22
            echo "\t\t<li class=\"list-group-item\"><strong>Scénariste : </strong>";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "scenariste", array()), "username", array()), "html", null, true);
            echo " / ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "scenariste", array()), "email", array()), "html", null, true);
            echo "</li>
\t";
        }
        // line 24
        echo "\t
\t<li class=\"list-group-item\">
\t\t<table class=\"table\">
\t\t\t<thead>
\t\t\t\t<tr>
\t\t\t\t\t<th>Utilisateur</th>
\t\t\t\t\t<th>Nom du personnage</th>
\t\t\t\t\t<th>Classe</th>
\t\t\t\t</tr>
\t\t\t</thead>
\t\t\t<tbody>
\t\t\t
\t\t\t\t";
        // line 36
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "participants", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["participant"]) {
            // line 37
            echo "\t\t\t\t\t<tr>\t\t\t\t\t\t
\t\t\t\t\t\t<td>";
            // line 38
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["participant"], "user", array()), "username", array()), "html", null, true);
            echo " / ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["participant"], "user", array()), "email", array()), "html", null, true);
            echo "</td>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t";
            // line 40
            if ($this->getAttribute($context["participant"], "personnage", array())) {
                // line 41
                echo "\t\t\t\t\t\t\t\t";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["participant"], "personnage", array()), "publicName", array()), "html", null, true);
                echo "
\t\t\t\t\t\t\t";
            }
            // line 43
            echo "\t\t\t\t\t\t</td>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t";
            // line 45
            if ($this->getAttribute($context["participant"], "personnage", array())) {
                // line 46
                echo "\t\t\t\t\t\t\t\t";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["participant"], "personnage", array()), "classeName", array()), "html", null, true);
                echo "
\t\t\t\t\t\t\t";
            }
            // line 48
            echo "\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['participant'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 51
        echo "\t\t\t</tbody>
\t\t</table>
\t</li>
</ul>

<div class=\"header\">
\t<h5>trombinoscope</h5>
</div>

<div class=\"row\">
";
        // line 61
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "participants", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["participant"]) {
            // line 62
            echo "\t<div class=\"col-xs-6 col-md-3\">
\t\t";
            // line 63
            if ($this->getAttribute($this->getAttribute($context["participant"], "user", array()), "trombineUrl", array())) {
                // line 64
                echo "\t\t\t<img width=\"160\" src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("trombine.get", array("trombine" => $this->getAttribute($this->getAttribute($context["participant"], "user", array()), "trombineUrl", array()))), "html", null, true);
                echo "\" />
\t\t\t<div class=\"caption\">
\t\t\t\t";
                // line 66
                if ($this->getAttribute($context["participant"], "personnage", array())) {
                    // line 67
                    echo "\t\t\t\t\t";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["participant"], "personnage", array()), "publicName", array()), "html", null, true);
                    echo "
\t\t\t\t";
                }
                // line 69
                echo "\t\t\t</div>
\t\t";
            }
            // line 71
            echo "\t</div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['participant'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 73
        echo "</div>

<br />

<div class=\"header\">
\t<h5>Territoire(s)</h5>
</div>

";
        // line 81
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "territoires", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["territoire"]) {
            // line 82
            echo "\t<h6>";
            echo twig_escape_filter($this->env, $this->getAttribute($context["territoire"], "nom", array()), "html", null, true);
            echo "</h6>
\t";
            // line 83
            echo $this->env->getExtension('markdown')->markdown($this->getAttribute($context["territoire"], "description", array()));
            echo "
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['territoire'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 85
        echo "\t\t\t\t
<div class=\"header\">
\t<h5>Vos alliés</h5>
</div>

<blockquote>
";
        // line 91
        if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "isResponsable", array(0 => (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe"))), "method")) {
            // line 92
            echo "\t<p>Vous disposez de <strong>";
            echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "alliances", array())), "html", null, true);
            echo "</strong> alliance(s) sur un maximum de <strong>3</strong>.</p>
\t<p>Vous pouvez choisir <strong>";
            // line 93
            echo twig_escape_filter($this->env, (twig_length_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "ennemies", array())) - twig_length_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "alliances", array()))), "html", null, true);
            echo "</strong> allié(s) supplémentaire(s).</p>
\t<p>Votre nombre d'ennemis doit toujours être supérieur à votre nombre d'alliés. Pour augmenter le nombe d'alliance possible (jusqu'à un maximum de 3), choisissez plus d'ennemis</p>
";
        } else {
            // line 96
            echo "\t<p>Votre chef de groupe a la possibilité de gérer les alliances.</p>
";
        }
        // line 98
        echo "</blockquote>

<ul class=\"list-group\">
";
        // line 101
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "alliances", array()));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["alliance"]) {
            // line 102
            echo "\t<li class=\"list-group-item\">
\t\t<div class=\"row\">
\t\t\t<div class=\"col-md-8\">
\t\t\t\t";
            // line 105
            if (($this->getAttribute($context["alliance"], "groupe", array()) == (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")))) {
                // line 106
                echo "\t\t\t\t\t";
                echo twig_escape_filter($this->env, $this->getAttribute($context["alliance"], "requestedGroupe", array()), "html", null, true);
                echo "
\t\t\t\t";
            } else {
                // line 108
                echo "\t\t\t\t\t";
                echo twig_escape_filter($this->env, $this->getAttribute($context["alliance"], "groupe", array()), "html", null, true);
                echo "
\t\t\t\t";
            }
            // line 110
            echo "\t\t\t</div>
\t\t\t<div class=\"col-md-4\">
\t\t\t\t<a href=\"";
            // line 112
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.breakAlliance", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()), "alliance" => $this->getAttribute($context["alliance"], "id", array()))), "html", null, true);
            echo "\">Rompre cette alliance</a>
\t\t\t</div>
\t\t</div>
\t</li>
\t\t
";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 118
            echo "\tVous n'avez aucun alliés.
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['alliance'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 120
        echo "</ul>

";
        // line 122
        if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "isResponsable", array(0 => (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe"))), "method")) {
            // line 123
            echo "
\t";
            // line 124
            if ((twig_length_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "waitingAlliances", array())) > 0)) {
                // line 125
                echo "\t\t<div class=\"header\">
\t\t\t<h5>Négociations en cours</h5>
\t\t</div>
\t\t
\t\t<ul class=\"list-group\">
\t\t";
                // line 130
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "waitingAlliances", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["alliance"]) {
                    // line 131
                    echo "\t\t\t<li class=\"list-group-item\">
\t\t\t\t";
                    // line 132
                    if (($this->getAttribute($context["alliance"], "groupe", array()) == (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")))) {
                        // line 133
                        echo "\t\t\t\t\t<div class=\"row\">
\t\t\t\t\t\t<div class=\"col-md-8\">
\t\t\t\t\t\t\tVous avez demandé une alliance avec <strong>";
                        // line 135
                        echo twig_escape_filter($this->env, $this->getAttribute($context["alliance"], "requestedGroupe", array()), "html", null, true);
                        echo "</strong>.
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"col-md-4\">
\t\t\t\t\t\t\t<a href=\"";
                        // line 138
                        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.cancelRequestedAlliance", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()), "alliance" => $this->getAttribute($context["alliance"], "id", array()))), "html", null, true);
                        echo "\">Annuler la demande</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t";
                    } else {
                        // line 142
                        echo "\t\t\t\t\t<div class=\"row\">
\t\t\t\t\t\t<div class=\"col-md-8\">
\t\t\t\t\t\t\t<strong>";
                        // line 144
                        echo twig_escape_filter($this->env, $this->getAttribute($context["alliance"], "groupe", array()), "html", null, true);
                        echo "</strong> vous a demandé une alliance.
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"col-md-4\">
\t\t\t\t\t\t\t<a href=\"";
                        // line 147
                        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.acceptAlliance", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()), "alliance" => $this->getAttribute($context["alliance"], "id", array()))), "html", null, true);
                        echo "\">Accepter la demande</a>
\t\t\t\t\t\t\t<a href=\"";
                        // line 148
                        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.refuseAlliance", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()), "alliance" => $this->getAttribute($context["alliance"], "id", array()))), "html", null, true);
                        echo "\">Refuser la demande</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t";
                    }
                    // line 152
                    echo "\t\t\t</li>
\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['alliance'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 153
                echo "\t
\t\t</ul>
\t";
            }
            // line 156
            echo "
";
        }
        // line 158
        echo "
<div class=\"header\">
\t<h5>Vos ennemis</h5>
</div>

<blockquote>
";
        // line 164
        if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "isResponsable", array(0 => (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe"))), "method")) {
            // line 165
            echo "\t<p>Vous disposez de <strong>";
            echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "ennemies", array())), "html", null, true);
            echo "</strong> ennemi(s) sur un maximum de <strong>5</strong>.</p>
\t<p>Si vous avez 3 ennemis ou plus, vous ne pouvez plus faire de Déclaration de guerre (mais vous pouvez toujours en recevoir)</p>
\t<p>Etre l’ennemi d’un groupe ne signifie pas que vous devez tuer à vue ses membres, simplement que vous avez un différent important à régler avec lui. Le but est de générer du jeu, pas de l’appauvrir en générant des massacres.</p>
";
        } else {
            // line 169
            echo "\t<p>Votre chef de groupe a la possibilité de gérer les alliances.</p>
";
        }
        // line 171
        echo "</blockquote>

<ul class=\"list-group\">
";
        // line 174
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "ennemies", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["ennemi"]) {
            // line 175
            echo "\t<li class=\"list-group-item\">
\t\t<div class=\"row\">
\t\t\t<div class=\"col-md-8\">
\t\t\t\t";
            // line 178
            if (($this->getAttribute($context["ennemi"], "groupe", array()) == (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")))) {
                // line 179
                echo "\t\t\t\t\tVous avez déclaré la guerre à <strong>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["ennemi"], "requestedGroupe", array()), "html", null, true);
                echo "</strong>.
\t\t\t\t";
            } else {
                // line 181
                echo "\t\t\t\t\t<strong>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["ennemi"], "groupe", array()), "html", null, true);
                echo "</strong> vous a déclaré la guerre.
\t\t\t\t";
            }
            // line 183
            echo "\t\t\t</div>
\t\t\t<div class=\"col-md-4\">
\t\t\t\t";
            // line 185
            if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "isResponsable", array(0 => (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe"))), "method")) {
                echo "<a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.requestPeace", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()), "enemy" => $this->getAttribute($context["ennemi"], "id", array()))), "html", null, true);
                echo "\">Proposer de faire la paix</a>";
            }
            // line 186
            echo "\t\t\t</div>
\t\t</div>
\t</li>\t
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['ennemi'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 190
        echo "</ul>

";
        // line 192
        if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "isResponsable", array(0 => (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe"))), "method")) {
            // line 193
            echo "
\t";
            // line 194
            if ((twig_length_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "waitingPeace", array())) > 0)) {
                // line 195
                echo "\t\t<div class=\"header\">
\t\t\t<h5>Négociations de paix en cours</h5>
\t\t</div>
\t\t
\t\t<ul class=\"list-group\">
\t\t";
                // line 200
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "waitingPeace", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["war"]) {
                    // line 201
                    echo "\t\t\t<li class=\"list-group-item\">
\t\t\t\t";
                    // line 202
                    if (($this->getAttribute($context["war"], "groupe", array()) == (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")))) {
                        // line 203
                        echo "\t\t\t\t\t<div class=\"row\">
\t\t\t\t\t\t<div class=\"col-md-8\">
\t\t\t\t\t\t\tVous avez demandé la paix avec <strong>";
                        // line 205
                        echo twig_escape_filter($this->env, $this->getAttribute($context["war"], "requestedGroupe", array()), "html", null, true);
                        echo "</strong>.
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"col-md-4\">
\t\t\t\t\t\t\t<a href=\"";
                        // line 208
                        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.cancelRequestedPeace", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()), "enemy" => $this->getAttribute($context["war"], "id", array()))), "html", null, true);
                        echo "\">Annuler la demande</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t";
                    } else {
                        // line 212
                        echo "\t\t\t\t\t<div class=\"row\">
\t\t\t\t\t\t<div class=\"col-md-8\">
\t\t\t\t\t\t\t<strong>";
                        // line 214
                        echo twig_escape_filter($this->env, $this->getAttribute($context["war"], "groupe", array()), "html", null, true);
                        echo "</strong> propose la paix.
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"col-md-4\">
\t\t\t\t\t\t\t<a href=\"";
                        // line 217
                        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.acceptPeace", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()), "enemy" => $this->getAttribute($context["war"], "id", array()))), "html", null, true);
                        echo "\">Accepter la demande</a>
\t\t\t\t\t\t\t<a href=\"";
                        // line 218
                        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.refusePeace", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()), "enemy" => $this->getAttribute($context["war"], "id", array()))), "html", null, true);
                        echo "\">Refuser la demande</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t";
                    }
                    // line 222
                    echo "\t\t\t</li>
\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['war'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 223
                echo "\t
\t\t</ul>
\t\t
\t";
            }
            // line 227
            echo "\t
\t";
            // line 228
            if ((twig_length_filter($this->env, $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "oldEnemies", array())) > 0)) {
                // line 229
                echo "\t\t<div class=\"header\">
\t\t\t<h5>Vos anciens ennemis</h5>
\t\t</div>
\t\t
\t\t<ul class=\"list-group\">
\t\t\t";
                // line 234
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "oldEnemies", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["war"]) {
                    // line 235
                    echo "\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t";
                    // line 236
                    if (($this->getAttribute($context["war"], "groupe", array()) == (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")))) {
                        // line 237
                        echo "\t\t\t\t\t\tVous avez fait la paix avec <strong>";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["war"], "requestedGroupe", array()), "html", null, true);
                        echo "</strong>.
\t\t\t\t\t";
                    } else {
                        // line 239
                        echo "\t\t\t\t\t\tVous avez fait la paix avec <strong>";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["war"], "groupe", array()), "html", null, true);
                        echo "</strong>.
\t\t\t\t\t";
                    }
                    // line 241
                    echo "\t\t\t\t</li>
\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['war'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 243
                echo "\t\t</ul>
\t";
            }
        }
        // line 246
        echo "
";
        // line 247
        if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "isResponsable", array(0 => (isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe"))), "method")) {
            // line 248
            echo "\t<a class=\"btn btn-success\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.requestAlliance", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()))), "html", null, true);
            echo "\">Demander une alliance</a>
\t<a class=\"btn btn-danger\" href=\"";
            // line 249
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.declareWar", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()))), "html", null, true);
            echo "\">Choisir un ennemi</a>
";
        }
    }

    public function getTemplateName()
    {
        return "public/groupe/fragment/groupe.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  557 => 249,  552 => 248,  550 => 247,  547 => 246,  542 => 243,  535 => 241,  529 => 239,  523 => 237,  521 => 236,  518 => 235,  514 => 234,  507 => 229,  505 => 228,  502 => 227,  496 => 223,  489 => 222,  482 => 218,  478 => 217,  472 => 214,  468 => 212,  461 => 208,  455 => 205,  451 => 203,  449 => 202,  446 => 201,  442 => 200,  435 => 195,  433 => 194,  430 => 193,  428 => 192,  424 => 190,  415 => 186,  409 => 185,  405 => 183,  399 => 181,  393 => 179,  391 => 178,  386 => 175,  382 => 174,  377 => 171,  373 => 169,  365 => 165,  363 => 164,  355 => 158,  351 => 156,  346 => 153,  339 => 152,  332 => 148,  328 => 147,  322 => 144,  318 => 142,  311 => 138,  305 => 135,  301 => 133,  299 => 132,  296 => 131,  292 => 130,  285 => 125,  283 => 124,  280 => 123,  278 => 122,  274 => 120,  267 => 118,  256 => 112,  252 => 110,  246 => 108,  240 => 106,  238 => 105,  233 => 102,  228 => 101,  223 => 98,  219 => 96,  213 => 93,  208 => 92,  206 => 91,  198 => 85,  190 => 83,  185 => 82,  181 => 81,  171 => 73,  164 => 71,  160 => 69,  154 => 67,  152 => 66,  146 => 64,  144 => 63,  141 => 62,  137 => 61,  125 => 51,  117 => 48,  111 => 46,  109 => 45,  105 => 43,  99 => 41,  97 => 40,  90 => 38,  87 => 37,  83 => 36,  69 => 24,  61 => 22,  58 => 21,  50 => 19,  48 => 18,  38 => 11,  35 => 10,  29 => 6,  27 => 5,  22 => 3,  19 => 2,);
    }
}
/* {# detail du groupe #}*/
/* */
/* <h4>{{ groupe.nom }}</h4>*/
/* */
/* {% if app.user.isResponsable(groupe) %}*/
/* <blockquote>*/
/* Vous êtes responsable de ce groupe.*/
/* </blockquote>*/
/* {% endif %}*/
/* */
/* <p>{{ groupe.description|markdown }}</p>*/
/* */
/* <div class="header">*/
/* 	<h5>Composition</h5>*/
/* </div>*/
/* */
/* <ul class="list-group">*/
/* 	{% if groupe.responsable %}*/
/* 		<li class="list-group-item"><strong>Responsable : </strong>{{ groupe.responsable.username }} / {{ groupe.responsable.email }}</li>*/
/* 	{% endif %}*/
/* 	{% if groupe.scenariste %}*/
/* 		<li class="list-group-item"><strong>Scénariste : </strong>{{ groupe.scenariste.username }} / {{ groupe.scenariste.email }}</li>*/
/* 	{% endif %}*/
/* 	*/
/* 	<li class="list-group-item">*/
/* 		<table class="table">*/
/* 			<thead>*/
/* 				<tr>*/
/* 					<th>Utilisateur</th>*/
/* 					<th>Nom du personnage</th>*/
/* 					<th>Classe</th>*/
/* 				</tr>*/
/* 			</thead>*/
/* 			<tbody>*/
/* 			*/
/* 				{% for participant in  groupe.participants %}*/
/* 					<tr>						*/
/* 						<td>{{ participant.user.username }} / {{ participant.user.email }}</td>*/
/* 						<td>*/
/* 							{% if participant.personnage %}*/
/* 								{{ participant.personnage.publicName }}*/
/* 							{% endif %}*/
/* 						</td>*/
/* 						<td>*/
/* 							{% if participant.personnage %}*/
/* 								{{ participant.personnage.classeName }}*/
/* 							{% endif %}*/
/* 						</td>*/
/* 					</tr>*/
/* 				{% endfor %}*/
/* 			</tbody>*/
/* 		</table>*/
/* 	</li>*/
/* </ul>*/
/* */
/* <div class="header">*/
/* 	<h5>trombinoscope</h5>*/
/* </div>*/
/* */
/* <div class="row">*/
/* {% for participant in  groupe.participants %}*/
/* 	<div class="col-xs-6 col-md-3">*/
/* 		{% if participant.user.trombineUrl %}*/
/* 			<img width="160" src="{{ path('trombine.get', {'trombine' : participant.user.trombineUrl }) }}" />*/
/* 			<div class="caption">*/
/* 				{% if participant.personnage %}*/
/* 					{{ participant.personnage.publicName }}*/
/* 				{% endif %}*/
/* 			</div>*/
/* 		{% endif %}*/
/* 	</div>*/
/* {% endfor %}*/
/* </div>*/
/* */
/* <br />*/
/* */
/* <div class="header">*/
/* 	<h5>Territoire(s)</h5>*/
/* </div>*/
/* */
/* {% for territoire in groupe.territoires %}*/
/* 	<h6>{{ territoire.nom }}</h6>*/
/* 	{{ territoire.description|markdown }}*/
/* {% endfor %}*/
/* 				*/
/* <div class="header">*/
/* 	<h5>Vos alliés</h5>*/
/* </div>*/
/* */
/* <blockquote>*/
/* {% if app.user.isResponsable(groupe) %}*/
/* 	<p>Vous disposez de <strong>{{ groupe.alliances|length }}</strong> alliance(s) sur un maximum de <strong>3</strong>.</p>*/
/* 	<p>Vous pouvez choisir <strong>{{ groupe.ennemies|length - groupe.alliances|length }}</strong> allié(s) supplémentaire(s).</p>*/
/* 	<p>Votre nombre d'ennemis doit toujours être supérieur à votre nombre d'alliés. Pour augmenter le nombe d'alliance possible (jusqu'à un maximum de 3), choisissez plus d'ennemis</p>*/
/* {% else %}*/
/* 	<p>Votre chef de groupe a la possibilité de gérer les alliances.</p>*/
/* {% endif %}*/
/* </blockquote>*/
/* */
/* <ul class="list-group">*/
/* {% for alliance in groupe.alliances %}*/
/* 	<li class="list-group-item">*/
/* 		<div class="row">*/
/* 			<div class="col-md-8">*/
/* 				{% if alliance.groupe == groupe %}*/
/* 					{{ alliance.requestedGroupe }}*/
/* 				{% else %}*/
/* 					{{ alliance.groupe }}*/
/* 				{% endif %}*/
/* 			</div>*/
/* 			<div class="col-md-4">*/
/* 				<a href="{{ path('groupe.breakAlliance', {'groupe': groupe.id, 'alliance': alliance.id}) }}">Rompre cette alliance</a>*/
/* 			</div>*/
/* 		</div>*/
/* 	</li>*/
/* 		*/
/* {% else %}*/
/* 	Vous n'avez aucun alliés.*/
/* {% endfor %}*/
/* </ul>*/
/* */
/* {% if app.user.isResponsable(groupe) %}*/
/* */
/* 	{% if groupe.waitingAlliances|length > 0 %}*/
/* 		<div class="header">*/
/* 			<h5>Négociations en cours</h5>*/
/* 		</div>*/
/* 		*/
/* 		<ul class="list-group">*/
/* 		{% for alliance in groupe.waitingAlliances %}*/
/* 			<li class="list-group-item">*/
/* 				{% if alliance.groupe == groupe %}*/
/* 					<div class="row">*/
/* 						<div class="col-md-8">*/
/* 							Vous avez demandé une alliance avec <strong>{{ alliance.requestedGroupe }}</strong>.*/
/* 						</div>*/
/* 						<div class="col-md-4">*/
/* 							<a href="{{ path('groupe.cancelRequestedAlliance', {'groupe': groupe.id, 'alliance': alliance.id}) }}">Annuler la demande</a>*/
/* 						</div>*/
/* 					</div>*/
/* 				{% else %}*/
/* 					<div class="row">*/
/* 						<div class="col-md-8">*/
/* 							<strong>{{ alliance.groupe }}</strong> vous a demandé une alliance.*/
/* 						</div>*/
/* 						<div class="col-md-4">*/
/* 							<a href="{{ path('groupe.acceptAlliance', {'groupe': groupe.id, 'alliance': alliance.id}) }}">Accepter la demande</a>*/
/* 							<a href="{{ path('groupe.refuseAlliance', {'groupe': groupe.id, 'alliance': alliance.id}) }}">Refuser la demande</a>*/
/* 						</div>*/
/* 					</div>*/
/* 				{% endif %}*/
/* 			</li>*/
/* 		{% endfor %}	*/
/* 		</ul>*/
/* 	{% endif %}*/
/* */
/* {% endif %}*/
/* */
/* <div class="header">*/
/* 	<h5>Vos ennemis</h5>*/
/* </div>*/
/* */
/* <blockquote>*/
/* {% if app.user.isResponsable(groupe) %}*/
/* 	<p>Vous disposez de <strong>{{ groupe.ennemies|length }}</strong> ennemi(s) sur un maximum de <strong>5</strong>.</p>*/
/* 	<p>Si vous avez 3 ennemis ou plus, vous ne pouvez plus faire de Déclaration de guerre (mais vous pouvez toujours en recevoir)</p>*/
/* 	<p>Etre l’ennemi d’un groupe ne signifie pas que vous devez tuer à vue ses membres, simplement que vous avez un différent important à régler avec lui. Le but est de générer du jeu, pas de l’appauvrir en générant des massacres.</p>*/
/* {% else %}*/
/* 	<p>Votre chef de groupe a la possibilité de gérer les alliances.</p>*/
/* {% endif %}*/
/* </blockquote>*/
/* */
/* <ul class="list-group">*/
/* {% for ennemi in groupe.ennemies %}*/
/* 	<li class="list-group-item">*/
/* 		<div class="row">*/
/* 			<div class="col-md-8">*/
/* 				{% if ennemi.groupe == groupe %}*/
/* 					Vous avez déclaré la guerre à <strong>{{ ennemi.requestedGroupe }}</strong>.*/
/* 				{% else %}*/
/* 					<strong>{{ ennemi.groupe }}</strong> vous a déclaré la guerre.*/
/* 				{% endif %}*/
/* 			</div>*/
/* 			<div class="col-md-4">*/
/* 				{% if app.user.isResponsable(groupe) %}<a href="{{ path('groupe.requestPeace', {'groupe': groupe.id, 'enemy': ennemi.id}) }}">Proposer de faire la paix</a>{% endif %}*/
/* 			</div>*/
/* 		</div>*/
/* 	</li>	*/
/* {% endfor %}*/
/* </ul>*/
/* */
/* {% if app.user.isResponsable(groupe) %}*/
/* */
/* 	{% if groupe.waitingPeace|length > 0 %}*/
/* 		<div class="header">*/
/* 			<h5>Négociations de paix en cours</h5>*/
/* 		</div>*/
/* 		*/
/* 		<ul class="list-group">*/
/* 		{% for war in groupe.waitingPeace %}*/
/* 			<li class="list-group-item">*/
/* 				{% if war.groupe == groupe %}*/
/* 					<div class="row">*/
/* 						<div class="col-md-8">*/
/* 							Vous avez demandé la paix avec <strong>{{ war.requestedGroupe }}</strong>.*/
/* 						</div>*/
/* 						<div class="col-md-4">*/
/* 							<a href="{{ path('groupe.cancelRequestedPeace', {'groupe': groupe.id, 'enemy': war.id}) }}">Annuler la demande</a>*/
/* 						</div>*/
/* 					</div>*/
/* 				{% else %}*/
/* 					<div class="row">*/
/* 						<div class="col-md-8">*/
/* 							<strong>{{ war.groupe }}</strong> propose la paix.*/
/* 						</div>*/
/* 						<div class="col-md-4">*/
/* 							<a href="{{ path('groupe.acceptPeace', {'groupe': groupe.id, 'enemy': war.id}) }}">Accepter la demande</a>*/
/* 							<a href="{{ path('groupe.refusePeace', {'groupe': groupe.id, 'enemy': war.id}) }}">Refuser la demande</a>*/
/* 						</div>*/
/* 					</div>*/
/* 				{% endif %}*/
/* 			</li>*/
/* 		{% endfor %}	*/
/* 		</ul>*/
/* 		*/
/* 	{% endif %}*/
/* 	*/
/* 	{% if groupe.oldEnemies|length > 0 %}*/
/* 		<div class="header">*/
/* 			<h5>Vos anciens ennemis</h5>*/
/* 		</div>*/
/* 		*/
/* 		<ul class="list-group">*/
/* 			{% for war in groupe.oldEnemies %}*/
/* 				<li class="list-group-item">*/
/* 					{% if war.groupe == groupe %}*/
/* 						Vous avez fait la paix avec <strong>{{ war.requestedGroupe }}</strong>.*/
/* 					{% else %}*/
/* 						Vous avez fait la paix avec <strong>{{ war.groupe }}</strong>.*/
/* 					{% endif %}*/
/* 				</li>*/
/* 			{% endfor %}*/
/* 		</ul>*/
/* 	{% endif %}*/
/* {% endif %}*/
/* */
/* {% if app.user.isResponsable(groupe) %}*/
/* 	<a class="btn btn-success" href="{{ path('groupe.requestAlliance', {'groupe': groupe.id}) }}">Demander une alliance</a>*/
/* 	<a class="btn btn-danger" href="{{ path('groupe.declareWar', {'groupe': groupe.id}) }}">Choisir un ennemi</a>*/
/* {% endif %}*/
/* */
