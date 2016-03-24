<?php

/* public/world.twig */
class __TwigTemplate_875c194c11d41d94edc80bcf436d83bb66a6c7a72c4bfd13ac452f46214b5a8d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "public/world.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'style' => array($this, 'block_style'),
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
        echo "Le monde";
    }

    // line 5
    public function block_style($context, array $blocks = array())
    {
        // line 6
        echo "
\t\t<meta name=\"viewport\" content=\"initial-scale=1.0, user-scalable=no\"/>

\t    <link rel=\"stylesheet\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/leaflet/leaflet.css\" />
        <link rel=\"stylesheet\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/leaflet/L.Control.MousePosition.css\" />
        <link rel=\"stylesheet\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/leaflet/leaflet.draw.css\" />
        <link rel=\"stylesheet\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/leaflet/Control.MiniMap.min.css\" />
        
\t\t<style>\t\t
\t\t\thtml, body, #content {
\t\t\t    height: 100%;
\t\t\t    width: 100%;
  \t\t\t\toverflow: hidden;
\t\t\t}
\t\t\tbody {
\t\t\t\tpadding-top: 50px;
\t\t\t}
\t\t\t
\t\t\t#map {
\t\t\t\twidth: auto;
  \t\t\t\theight: 100%;
\t\t\t}
\t\t\t.navbar {
\t\t\t\tmargin-bottom:0px;
\t\t\t\tmargin-top: -50px;
\t\t\t}
\t\t\t#content {
\t\t\t\tpadding: 0 0 0 0;
\t\t\t}
\t\t\t
\t\t\t.info, .territoire, .fief, .itineraire, .route, .fortification {
\t\t\t\tpadding: 6px 8px;
\t\t\t\tfont: 14px/16px Arial, Helvetica, sans-serif;
\t\t\t\tbackground: white;
\t\t\t\tbackground: rgba(255,255,255,0.8);
\t\t\t\tbox-shadow: 0 0 15px rgba(0,0,0,0.2);
\t\t\t\tborder-radius: 5px;
\t\t\t\twidth: 300px;
\t\t\t}
\t\t\t.info h4, .territoire h4, .fief h4, .itineraire h4, .route h4, .fortification h4 {
\t\t\t\tmargin: 0 0 5px;
\t\t\t\tcolor: #777;
\t\t\t}
\t\t\t
\t\t\t.info {
\t\t\t\twidth :500px;
\t\t\t}
\t\t\t
\t\t\t.geom {
\t\t\t\tdisplay: none;
\t\t\t}
\t\t\t
\t\t\t.leaflet-draw-toolbar .leaflet-draw-draw-territoire {
\t\t\t\tbackground-position: -31px -2px;
\t\t\t}
\t\t\t
\t\t\t.leaflet-draw-toolbar .leaflet-draw-draw-fief {
\t\t\t\tbackground-position: -31px -2px;
\t\t\t}
\t\t\t
\t\t\t.leaflet-draw-toolbar .leaflet-draw-draw-route {
\t\t\t\tbackground-position: 0 -1px;
\t\t\t}
\t\t\t
\t\t\t.leaflet-draw-toolbar .leaflet-draw-draw-itineraire {
\t\t\t\tbackground-position: 0 -1px;
\t\t\t}

\t\t\t.leaflet-draw-toolbar .leaflet-draw-draw-fortification {
\t\t\t\tbackground-position: -122px -2px;
\t\t\t}
\t\t\t
\t\t\t.leaflet-touch .leaflet-draw-toolbar .leaflet-draw-draw-fortification {
\t\t\t\tbackground-position: -120px -1px;
\t\t\t}

\t\t</style>
";
    }

    // line 85
    public function block_content($context, array $blocks = array())
    {
        // line 86
        echo "\t\t<div id=\"map\"></div>
";
    }

    // line 89
    public function block_javascript($context, array $blocks = array())
    {
        // line 90
        echo "


<script src=\"";
        // line 93
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/leaflet/leaflet.js\"></script>
<script src=\"";
        // line 94
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/leaflet/L.Control.MousePosition.js\"></script>
<script src=\"";
        // line 95
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/leaflet/leaflet.draw.js\"></script>
<script src=\"";
        // line 96
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/leaflet/Control.MiniMap.min.js\"></script>
<script src=\"";
        // line 97
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/randomColor.min.js\"></script>


<script>


\tvar cartographie = {
\t\t\t// Url du tileset
\t\t\tmapUrl: 'img/map/{z}/{x}/{y}.png',

\t\t\tmapCleanUrl: 'img/map_clean/{z}/{x}/{y}.png',
\t\t\t
\t\t\t// Zoom minimal
\t\t\tmapMinZoom: 0,
\t\t\t\t
\t\t\t// zoom maximal
\t\t\tmapMaxZoom : 6,

\t\t\t// zoom au chargement de la carte
\t\t\tmapNormalZoom: 2,
\t\t\t
\t\t\t// Liste des pays\t
\t\t\tcountriesList : Object(),

\t\t\t// Liste des fiefs  \t
\t\t\tfiefsList : Object(),

\t\t\t// Collection des geometries des pays\t\t
\t\t\tcountriesGeom: null,

\t\t\t// Collection des geometries des fiefs
\t\t\tfiefsGeom: null,

\t\t\t// Tilelayer de base
\t\t\tbaseTileLayer: null,

\t\t\t// Tilelayer de travail
\t\t\tworkingTileLayer: null,

\t\t\tminimapLayer: null,

\t\t\t// carte
\t\t\tmap: null,

\t\t\t// limites de la carte
\t\t\tmapBounds : null,\t

\t\t\t// Panneau d'information
\t\t\tinfoPanel : null,
\t\t\tinfoPanelDiv: null,

\t\t\t// Panneau de sauvegarde d'une geometrie de territoire
\t\t\tsaveTerritoirePanel : null,
\t\t\tsaveTerritoirePanelDiv: null,

\t\t\t// Panneau de sauvegarde d'une geometrie de territoire
\t\t\tsaveFiefPanel : null,
\t\t\tsaveFiefPanelDiv: null,

\t\t\t// Panneau de sauvegarde d'une geometrie de territoire
\t\t\tsaveRoutePanel : null,
\t\t\tsaveRoutePanelDiv: null,

\t\t\t// Panneau de calcul de distance & itineraire
\t\t\titinerairePanel: null,
\t\t\titinerairePanelDiv: null,

\t\t\t// Panneau de sauvegarde d'une geometrie de territoire
\t\t\tsaveFortificationPanel : null,
\t\t\tsaveFortificationPanelDiv: null,\t\t\t\t\t\t

\t\t\t// Geometrie courante
\t\t\tcurrentGeom : null,

\t\t\t// Collection des geometrie dessiné
\t\t\tdrawnItems : null,


\t\t\t// Création de la carte
\t\t\tcreateMap: function() {
\t\t\t\tthis.map = L.map('map', {
\t\t\t\t\t  maxZoom: this.mapMaxZoom,
\t\t\t\t\t  minZoom: this.mapMinZoom,
\t\t\t\t\t  crs: L.CRS.Simple,
\t\t\t\t\t  detectRetina: true
\t\t\t\t\t});
\t\t\t},
\t\t\t
\t\t\t// Création des limites de la carte
\t\t\tcreateMapBounds: function() {
\t\t\t\tthis.mapBounds = new L.LatLngBounds(
\t\t\t\t\t\tthis.map.unproject([0,11536], this.mapMaxZoom),
\t\t\t\t\t\tthis.map.unproject([16384,0], this.mapMaxZoom)\t    
\t\t\t\t\t);
\t\t\t\tthis.map.setMaxBounds(this.mapBounds);

\t\t\t\tvar _mapCenter = this.map.unproject([16384/2, 11536/2], this.mapMaxZoom);
\t\t\t\tthis.map.setView(_mapCenter, this.mapNormalZoom);
\t\t\t},
\t\t\t
\t\t\t// Création du tileLayer de base
\t\t\tcreateBaseTileLayer : function() {
\t\t\t\t
\t\t\t\tthis.baseTileLayer = L.tileLayer(this.mapUrl, {
\t\t\t\t\tminZoom: this.mapMinZoom, 
\t\t\t        maxZoom: this.mapMaxZoom,
\t\t\t        bounds: this.mapBounds,
\t\t\t        attribution: 'Rendered with <a href=\"http://www.gdal.org/gdal2tiles.html\">Gdal2Tile</a> | Icons made by <a href=\"http://www.freepik.com\" title=\"Freepik\">Freepik</a> from <a href=\"http://www.flaticon.com\" title=\"Flaticon\">www.flaticon.com</a> is licensed by <a href=\"http://creativecommons.org/licenses/by/3.0/\" title=\"Creative Commons BY 3.0\" target=\"_blank\">CC 3.0 BY</a>',
\t\t\t        tms: false,
\t\t\t        continuousWorld: true,
\t\t\t        noWrap: false,
\t\t\t        tilesize:255,
\t\t\t        crs: L.CRS.Simple,
\t\t\t        detectRetina: false
\t\t\t\t}).addTo(this.map);
\t\t\t},

\t\t\t// Création du tileLayer de base
\t\t\tcreateWorkingTileLayer : function() {
\t\t\t\t
\t\t\t\tthis.workingTileLayer = L.tileLayer(this.mapCleanUrl, {
\t\t\t\t\tminZoom: this.mapMinZoom, 
\t\t\t        maxZoom: this.mapMaxZoom,
\t\t\t        bounds: this.mapBounds,
\t\t\t        attribution: 'Rendered with <a href=\"http://www.gdal.org/gdal2tiles.html\">Gdal2Tile</a> | Icons made by <a href=\"http://www.freepik.com\" title=\"Freepik\">Freepik</a> from <a href=\"http://www.flaticon.com\" title=\"Flaticon\">www.flaticon.com</a> is licensed by <a href=\"http://creativecommons.org/licenses/by/3.0/\" title=\"Creative Commons BY 3.0\" target=\"_blank\">CC 3.0 BY</a>',
\t\t\t        tms: false,
\t\t\t        continuousWorld: true,
\t\t\t        noWrap: false,
\t\t\t        tilesize:255,
\t\t\t        crs: L.CRS.Simple,
\t\t\t        detectRetina: false
\t\t\t\t}).addTo(this.map);
\t\t\t},

\t\t\tcreateMinimapLayer : function() {
\t\t\t\t
\t\t\t\tthis.minimapLayer = L.tileLayer(this.mapCleanUrl, {
\t\t\t\t\tminZoom: this.mapMinZoom, 
\t\t\t        maxZoom: this.mapMaxZoom,
\t\t\t        bounds: this.mapBounds,
\t\t\t        attribution: 'Rendered with <a href=\"http://www.gdal.org/gdal2tiles.html\">Gdal2Tile</a> | Icons made by <a href=\"http://www.freepik.com\" title=\"Freepik\">Freepik</a> from <a href=\"http://www.flaticon.com\" title=\"Flaticon\">www.flaticon.com</a> is licensed by <a href=\"http://creativecommons.org/licenses/by/3.0/\" title=\"Creative Commons BY 3.0\" target=\"_blank\">CC 3.0 BY</a>',
\t\t\t        tms: false,
\t\t\t        continuousWorld: true,
\t\t\t        noWrap: false,
\t\t\t        tilesize:255,
\t\t\t        crs: L.CRS.Simple,
\t\t\t        detectRetina: false
\t\t\t\t});
\t\t\t},

\t\t\t// Applique les limites de la carte à la carte
\t\t\tfitMap: function() {
\t\t\t\tthis.map.fitBounds(this.mapBounds);
\t\t\t},

\t\t\t// méthode déclenchée pour toute nouvelle geometrie
\t\t\t// Permet de lier des événements à une géométrie
\t\t\tonEachFeature: function(feature, layer) {
\t\t\t    if (feature.properties ) {
\t\t\t\t    var editLink = \"";
        // line 256
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/territoire/\"+feature.properties.id+\"/update\";

\t\t\t        layer.bindPopup(
\t\t\t\t\t        '<strong>'+feature.properties.name+'</strong>' 
\t\t\t\t\t        ";
        // line 260
        if ($this->env->getExtension('security')->isGranted("ROLE_ORGA", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            // line 261
            echo "\t\t\t\t\t        \t+'<br /><a href=\"'+editLink+'\">Modifier</a>'
\t\t\t\t\t        ";
        }
        // line 262
        echo ");
\t\t\t        layer.on({
\t\t\t\t        mouseover: cartographie.mouseOver,
\t\t\t\t\t    mouseout: cartographie.mouseOut
\t\t\t\t    });
\t\t\t    }
\t\t\t},
\t\t\t
\t\t\t// evenement mouseover sur une geometrie
\t\t\tmouseOver: function(e) {
\t\t\t\tvar layer = e.target;
\t\t\t\t//console.log(layer);
\t\t\t\tcartographie.displayInfoPanel(layer.feature.properties);
\t\t\t\t\$(cartographie.infoPanelDiv).show();
\t\t\t},
\t\t\t
\t\t\t// evenement mouseout sur une geometrie
\t\t\tmouseOut: function(e) {
\t\t\t\t\$(cartographie.infoPanelDiv).hide();
\t\t\t},

\t\t\t// Creation des controles de dessins
\t\t\tcreateDrawControl: function() {

\t\t\t\tL.Draw.Territoire = L.Draw.Polygon.extend({
\t\t\t\t\t initialize: function (map, options) {
\t\t\t\t\t\t L.Draw.Polygon.prototype.initialize.call(this, map, options);
\t\t\t\t          this.type = 'territoire';
\t\t\t\t      }
\t\t\t\t});
\t\t\t\t
\t\t\t\tL.Draw.Fief = L.Draw.Polygon.extend({
\t\t\t\t\t initialize: function (map, options) {
\t\t\t\t\t\t L.Draw.Polygon.prototype.initialize.call(this, map, options);
\t\t\t\t          this.type = 'fief';
\t\t\t\t      }
\t\t\t\t});

\t\t\t\tL.Draw.Itineraire = L.Draw.Polyline.extend({
\t\t\t\t\t initialize: function (map, options) {
\t\t\t\t\t\t L.Draw.Polyline.prototype.initialize.call(this, map, options);
\t\t\t\t         this.type = 'itineraire';
\t\t\t\t      }
\t\t\t\t});
\t\t\t\t
\t\t\t\tL.Draw.Route = L.Draw.Polyline.extend({
\t\t\t\t\t initialize: function (map, options) {
\t\t\t\t\t\t L.Draw.Polyline.prototype.initialize.call(this, map, options);
\t\t\t\t          this.type = 'route';
\t\t\t\t      }
\t\t\t\t});
\t\t\t\t
\t\t\t\tL.Draw.Fortification = L.Draw.Marker.extend({
\t\t\t\t\t initialize: function (map, options) {
\t\t\t\t\t\t L.Draw.Marker.prototype.initialize.call(this, map, options);
\t\t\t\t          this.type = 'fortification';
\t\t\t\t      }
\t\t\t\t});
\t\t\t
\t\t\t\tvar FortificationMarker = L.Icon.extend({
\t\t\t\t    options: {
\t\t\t\t        shadowUrl: null,
\t\t\t\t        iconAnchor: new L.Point(12, 12),
\t\t\t\t        iconSize: new L.Point(32, 32),
\t\t\t\t        iconUrl: 'img/buildings.svg'
\t\t\t\t    }
\t\t\t\t});

\t\t\t\t
\t\t\t\tL.DrawToolbar.include({
\t\t\t\t    getModeHandlers: function (map) {
\t\t\t\t        return [
\t\t\t\t            {
\t\t\t\t                enabled: true,
\t\t\t\t                handler: new L.Draw.Territoire(map),
\t\t\t\t                title: 'Tracer un territoire'
\t\t\t\t            },
\t\t\t\t            {
\t\t\t\t                enabled: true,
\t\t\t\t                handler: new L.Draw.Fief(map),
\t\t\t\t                title: 'Tracer un fief'
\t\t\t\t            },
\t\t\t\t            {
\t\t\t\t                enabled: true,
\t\t\t\t                handler: new L.Draw.Itineraire(map, {
\t\t\t\t                \tshapeOptions: {
\t\t\t\t                \t\tweight: 6,
\t\t\t\t                \t\tcolor: '#3d86c2',\t
\t\t\t\t                \t\topacity: 0.8,
\t\t\t\t                \t},
\t\t\t\t\t                showLength: false}),
\t\t\t\t                title: 'Calculer un itineraire'
\t\t\t\t            },
\t\t\t\t            {
\t\t\t\t                enabled: true,
\t\t\t\t                handler: new L.Draw.Route(map),
\t\t\t\t                title: 'Tracer une route commerciale'
\t\t\t\t            },
\t\t\t\t            {
\t\t\t\t                enabled: true,
\t\t\t\t                handler: new L.Draw.Fortification(map, {icon: new FortificationMarker}),
\t\t\t\t                title: 'Placer une fortification'
\t\t\t\t            }
\t\t\t\t        ];
\t\t\t\t    }
\t\t\t\t});
\t\t\t\t
\t\t\t\tthis.drawControl = new L.Control.Draw({
\t\t\t\t    edit: {
\t\t\t\t        featureGroup: this.drawnItems
\t\t\t\t    },
\t\t\t\t    draw: {
\t\t\t\t\t    polygon: {
\t\t\t\t\t    \tshapeOptions: {
\t\t\t\t                color: '#bada55'
\t\t\t\t            }
\t\t\t\t\t\t}
\t\t\t\t    }
\t\t\t\t});
\t\t\t},\t\t\t
\t\t\t
\t\t\t// Création des collections de geometries
\t\t\tcreateGeomCollection: function() {
\t\t\t\tthis.countriesGeom = new L.geoJson(false, {style: this.territoireStyle, onEachFeature: this.onEachFeature});
\t\t\t\tthis.fiefsGeom = new L.geoJson(false, {style: this.fiefStyle, onEachFeature: this.onEachFeature});
\t\t\t\tthis.routesGeom = new L.geoJson(false, {onEachFeature: this.onEachFeature});
\t\t\t\tthis.fortificationsGeom = new L.geoJson(false, {onEachFeature: this.onEachFeature});
\t\t\t\tthis.drawnItems = new L.FeatureGroup();
\t\t\t},

\t\t\t// style des territoires
\t\t\tterritoireStyle: function(feature) {
\t\t\t\tif ( feature.properties.color == null) {
\t\t\t\t\tfeature.properties.color = randomColor({luminosity:'dark'});
\t\t\t\t}
\t\t\t\treturn {
\t                weight: 5,
\t                opacity: 1,
\t                color: feature.properties.color,
\t                dashArray: '10',
\t                fillOpacity: 0.2,
\t                fillColor: feature.properties.color
\t            };
\t\t\t},
\t\t\t
\t\t\t// style des fiefs
\t\t\tfiefStyle: function(feature){
\t\t\t\tif ( feature.properties.color == null) {
\t\t\t\t\tfeature.properties.color = randomColor({luminosity:'dark'});
\t\t\t\t}
\t\t\t\treturn {
\t                weight: 2,
\t                opacity: 1,
\t                color: feature.properties.color,
\t                dashArray: '3',
\t                fillOpacity: 0,
\t                fillColor: '#666666'
\t            };
\t\t\t},

\t\t\t// Création du panneau d'information
\t\t\tcreateInfoPanel: function() {
\t\t\t\tthis.infoPanel = L.control();
\t\t\t\tthis.infoPanel.onAdd = function(map) {
\t\t\t\t\tcartographie.infoPanelDiv = L.DomUtil.create('div', 'info');
\t\t\t\t\t//cartographie.infoPanelDiv.innerHTML = '';
\t\t\t\t\t\$(cartographie.infoPanelDiv).hide();
\t\t\t\t\treturn cartographie.infoPanelDiv;
\t\t\t\t};
\t\t\t},

\t\t\tdisplayInfoPanel: function(props)
\t\t\t{
\t\t\t\tif ( props && ! props.description ) props.description = 'Aucune description';
\t\t\t\tcartographie.infoPanelDiv.innerHTML = ('<h4>' + props.name + '</h4>'
\t\t\t\t\t\t+ '<p>' + props.description + '</p>');
\t\t\t},

\t\t\t// Création du pannel de sauvegarde d'une geometrie territoire
\t\t\tcreateSaveTerritoirePanel: function() {
\t\t\t\tthis.saveTerritoirePanel = L.control();
\t\t\t\tthis.saveTerritoirePanel.onAdd = function(map) {
\t\t\t\t\tcartographie.saveTerritoirePanelDiv = L.DomUtil.create('div', 'territoire');
\t\t\t\t\tcartographie.saveTerritoirePanelDiv.innerHTML = '';
\t\t\t\t\t\$(cartographie.saveTerritoirePanelDiv).hide();
\t\t\t\t\tthis.currentGeom = null;
\t\t\t\t\treturn cartographie.saveTerritoirePanelDiv;
\t\t\t\t};
\t\t\t},

\t\t\t// Création du pannel de sauvegarde d'une geometrie fief
\t\t\tcreateSaveFiefPanel: function() {
\t\t\t\tthis.saveFiefPanel = L.control();
\t\t\t\tthis.saveFiefPanel.onAdd = function(map) {
\t\t\t\t\tcartographie.saveFiefPanelDiv = L.DomUtil.create('div', 'fief');
\t\t\t\t\tcartographie.saveFiefPanelDiv.innerHTML = '';
\t\t\t\t\t\$(cartographie.saveFiefPanelDiv).hide();
\t\t\t\t\tthis.currentGeom = null;
\t\t\t\t\treturn cartographie.saveFiefPanelDiv;
\t\t\t\t};
\t\t\t},

\t\t\t// Création du panneau itineraire et distance
\t\t\tcreateItinerairePanel: function() {
\t\t\t\tthis.itinerairePanel = L.control({'position':'bottomleft'});
\t\t\t\tthis.itinerairePanel.onAdd = function(map) {
\t\t\t\t\tcartographie.itinerairePanelDiv = L.DomUtil.create('div','itineraire');
\t\t\t\t\tcartographie.itinerairePanelDiv.innerHTML = '';
\t\t\t\t\t\$(cartographie.itinerairePanelDiv).hide();
\t\t\t\t\tthis.currentGeom = null;
\t\t\t\t\treturn cartographie.itinerairePanelDiv;
\t\t\t\t};
\t\t\t},

\t\t\t// Création du pannel de sauvegarde d'une geometrie route
\t\t\tcreateSaveRoutePanel: function() {
\t\t\t\tthis.saveRoutePanel = L.control();
\t\t\t\tthis.saveRoutePanel.onAdd = function(map) {
\t\t\t\t\tcartographie.saveRoutePanelDiv = L.DomUtil.create('div', 'route');
\t\t\t\t\tcartographie.saveRoutePanelDiv.innerHTML = '';
\t\t\t\t\t\$(cartographie.saveRoutePanelDiv).hide();
\t\t\t\t\tthis.currentGeom = null;
\t\t\t\t\treturn cartographie.saveRoutePanelDiv;
\t\t\t\t};
\t\t\t},

\t\t\t// Création du pannel de sauvegarde d'une geometrie fortification
\t\t\tcreateSaveFortificationPanel: function() {
\t\t\t\tthis.saveFortificationPanel = L.control();
\t\t\t\tthis.saveFortificationPanel.onAdd = function(map) {
\t\t\t\t\tcartographie.saveFortificationPanelDiv = L.DomUtil.create('div', 'fortification');
\t\t\t\t\tcartographie.saveFortificationPanelDiv.innerHTML = '';
\t\t\t\t\t\$(cartographie.saveFortificationPanelDiv).hide();
\t\t\t\t\tthis.currentGeom = null;
\t\t\t\t\treturn cartographie.saveFortificationPanelDiv;
\t\t\t\t};
\t\t\t},
\t\t\t
\t\t\t// Affiche le panneau de sauvegarde d'une geometrie territorie
\t\t\tdisplaySaveTerritoirePanel: function(geom) {
\t\t\t\t\tthis.currentGeom = geom;
\t\t\t\t\tvar select = '<label>Choisissez le territoire : </label>';
\t\t\t\t\tselect = select + '<select id=\"selectedCountry\">';
\t\t\t\t\tfor(var key in this.countriesList)
\t\t\t\t\t{
\t\t\t\t\t\tselect = select + '<option value=\"'+this.countriesList[key].id+'\">'+this.countriesList[key].name+'</option>'
\t\t\t\t\t}
\t\t\t\t\tselect = select +  '</select>';
\t\t\t\t\tselect = select + '<input type=\"submit\" value=\"Sauver\" onclick=\"cartographie.saveTerritoire()\"/>'
\t\t\t\t\t
\t\t\t\t\tcartographie.saveTerritoirePanelDiv.innerHTML = select;\t\t\t
\t\t\t\t\t
\t\t\t\t\t\$(cartographie.saveTerritoirePanelDiv).show();
\t\t\t},

\t\t\t// Affiche le panneau de sauvegarde d'une geometrie territorie
\t\t\tdisplaySaveFiefPanel: function(geom) {
\t\t\t\t\tthis.currentGeom = geom;
\t\t\t\t\tvar select = '<label>Choisissez le fief : </label>';
\t\t\t\t\tselect = select + '<select id=\"selectedFief\">';
\t\t\t\t\tfor(var key in this.fiefsList)
\t\t\t\t\t{
\t\t\t\t\t\tselect = select + '<option value=\"'+this.fiefsList[key].id+'\">'+this.fiefsList[key].name+'</option>'
\t\t\t\t\t}
\t\t\t\t\tselect = select +  '</select>';
\t\t\t\t\tselect = select + '<input type=\"submit\" value=\"Sauver\" onclick=\"cartographie.saveFief()\"/>'
\t\t\t\t\t
\t\t\t\t\tcartographie.saveFiefPanelDiv.innerHTML = select;\t\t\t
\t\t\t\t\t
\t\t\t\t\t\$(cartographie.saveFiefPanelDiv).show();
\t\t\t},

\t\t\t// calcul de la distance entre deux points
\t\t\tdistance: function(latlngs) {
\t\t\t\tif ( latlngs.length <= 1) return 0;
\t\t\t\tvar refPoint = latlngs[0];
\t\t\t\tvar distanceTotal = 0;
\t\t\t\t
\t\t\t\tfor (i=1; i< latlngs.length; i++) 
\t\t\t\t{
\t\t\t\t\tvar Ay = (refPoint.lat *  -11536) / 180;
\t\t\t\t\tvar Ax = (refPoint.lng * 16384) / 255;

\t\t\t\t\tvar By = (latlngs[i].lat *  -11536) / 180;
\t\t\t\t\tvar Bx = (latlngs[i].lng * 16384) / 255;
\t\t\t\t\t
\t\t\t\t\tvar distance = Math.sqrt(Math.pow(Bx - Ax,2) + Math.pow(By - Ay,2));
\t\t\t\t\t
\t\t\t\t\tdistanceTotal += distance;
\t\t\t\t\trefPoint = latlngs[i];
\t\t\t\t}
\t\t\t\tdistanceTotal *= 1386.47; 
\t\t\t\treturn distanceTotal;
\t\t\t},

\t\t\t// calcul le temps necessaire pour parcourir une distance en fonction du type de moyen de transport
\t\t\tcalculTemps: function(distance, transport, heureParJour)
\t\t\t{
\t\t\t\tvar heure = 0, jour = 0;
\t\t\t\tswitch(transport) {
\t\t\t\t\tcase \"0\": // pied 5km/heure
\t\t\t\t\t\theure = Math.round(distance / 5);
\t\t\t\t\t\tbreak;
\t\t\t\t\tcase \"1\": // cheval 15km/heure
\t\t\t\t\t\theure = Math.round(distance / 15);
\t\t\t\t\t\tbreak;
\t\t\t\t\tcase \"2\": // cavalerie normal 6.25Km/heure
\t\t\t\t\t\theure = Math.round(distance / 6.25);
\t\t\t\t\t\tbreak;
\t\t\t\t\tcase \"3\": // cavalerie forcée 9.4Km/heure
\t\t\t\t\t\theure = Math.round(distance / 9.4);
\t\t\t\t\t\tbreak;
\t\t\t\t\tcase \"4\": // cavalerie bagage 3km/heure
\t\t\t\t\t\theure = Math.round(distance / 3);
\t\t\t\t\t\tbreak;
\t\t\t\t\tcase \"5\": // infanterie normale 2.5km/heure
\t\t\t\t\t\theure = Math.round(distance / 2.5);
\t\t\t\t\t\tbreak;
\t\t\t\t\tcase \"6\": // infanterie forcée 5km/h
\t\t\t\t\t\theure = Math.round(distance / 5);
\t\t\t\t\t\tbreak;
\t\t\t\t}

\t\t\t\tif ( heure > heureParJour ) {
\t\t\t\t\tjour = Math.round(heure / heureParJour);
\t\t\t\t\theure = heure % heureParJour;
\t\t\t\t}

\t\t\t\tvar text = '';
\t\t\t\tif ( jour > 0 ) text = jour+\" jour(s) et \";
\t\t\t\tif ( heure == 0 ) text += \"moins d'une heure\";
\t\t\t\telse text += heure + \" heures\";
\t\t\t\t
\t\t\t\t\$('#tempsVoyage').text(text);
\t\t\t},
\t\t\t
\t\t\t// Affiche le panneau de calcul du temps de voyage
\t\t\tdisplayItinerairePanel: function(layer)
\t\t\t{
\t\t\t\tvar distance = this.distance(layer._latlngs);
\t\t\t\tdistance = Math.round((distance / 1000)*100)/100;
\t\t\t\tvar select = '<h4>Détail de votre itineraire</h4>';
\t\t\t\tselect += '<strong>Distance :</strong><br /><span style=\"color:green\">' + distance + ' Km'+'</span><br />';
\t\t\t\tselect += '<label>Moyen de transport</label>'
\t\t\t\t\t+ '<select id=\"selectTempsVoyage\">'
\t\t\t\t\t+ '<option value=\"0\">Pied</option>'
\t\t\t\t\t+ '<option value=\"1\">Cheval</option>'
\t\t\t\t\t+ '<option value=\"2\">Cavalerie (Marche normale)</option>'
\t\t\t\t\t+ '<option value=\"3\">Cavalerie (Marche forcée)</option>'
\t\t\t\t\t+ '<option value=\"4\">Cavalerie (Bagages)</option>'
\t\t\t\t\t+ '<option value=\"5\">Infanterie (Marche normale)</option>'
\t\t\t\t\t+ '<option value=\"6\">Infanterie (Marche forcée)</option>'
\t\t\t\t\t+ '</select>'
\t\t\t\t\t+ '<label>Durée de la journée</label>'
\t\t\t\t\t+ '<input type=\"number\" id=\"heureParJour\" value=\"8\" size=\"2\"></input><br />'
\t\t\t\t\t+ '<strong>Durée du voyage</strong><br />'
\t\t\t\t\t+ '<div id=\"tempsVoyage\" style=\"color:green;\"></div>'
\t\t\t\t\t+ '<a href=\"#\" onclick=\"cartographie.closeItinerairePanel()\" style=\"float: right;\">Fermer</a>';
\t\t\t\t\t
\t\t\t\tcartographie.itinerairePanelDiv.innerHTML = select;
\t\t\t\t\$(cartographie.itinerairePanelDiv).show();

\t\t\t\tcartographie.calculTemps(distance, \$(\"#selectTempsVoyage\").val(), 8);

\t\t\t\t\$('#heureParJour').change(function() {
\t\t\t\t\tvar heureParJour = \$(this).val();
\t\t\t\t\tcartographie.calculTemps(distance, \$('#selectTempsVoyage').val(), heureParJour);
\t\t\t\t});
\t\t\t\t
\t\t\t\t\$('#selectTempsVoyage').change(function() {
\t\t\t\t\tvar heureParJour = \$(\"#heureParJour\").val();
\t\t\t\t\tcartographie.calculTemps(distance, \$(this).val(), heureParJour);
\t\t\t\t});
\t\t\t},

\t\t\t// Ferme le panneau itineraire
\t\t\tcloseItinerairePanel: function()
\t\t\t{
\t\t\t\tcartographie.itinerairePanelDiv.innerHtml = \"\";
\t\t\t\t\$(cartographie.itinerairePanelDiv).hide();
\t\t\t},
\t\t\t
\t\t\t// Affiche le panneau de sauvegarde d'une geometrie territorie
\t\t\tdisplaySaveRoutePanel: function(geom) {
\t\t\t\t\tthis.currentGeom = geom;
\t\t\t\t\tvar select = '<p>Non disponible</p>';
\t\t\t\t\tcartographie.saveRoutePanelDiv.innerHTML = select;\t\t\t
\t\t\t\t\t
\t\t\t\t\t\$(cartographie.saveRoutePanelDiv).show();
\t\t\t},

\t\t\t// Affiche le panneau de sauvegarde d'une geometrie territorie
\t\t\tdisplaySaveFortificationPanel: function(geom) {
\t\t\t\tthis.currentGeom = geom;
\t\t\t\tvar select = '<p>Non disponible</p>';
\t\t\t\tcartographie.saveFortificationPanelDiv.innerHTML = select;\t\t\t
\t\t\t\t
\t\t\t\t\$(cartographie.saveFortificationPanelDiv).show();
\t\t\t},

\t\t\t// Chargement des pays
\t\t\t// Les geometries sont stocké dans les collections correspondates
\t\t\t// La liste des pays est mise à jour
\t\t\tloadCountries: function() {
\t\t\t\t\$.ajax({
\t\t\t\t\tdataType: \"json\",
\t\t\t\t\turl: \"world/countries.json\",
\t\t\t\t\tsuccess: function(data) {
\t\t\t\t\t    \$(data).each(function(key, country) {
\t\t\t\t\t\t    if ( country.geom != null )
\t\t\t\t\t\t    {
\t\t\t\t\t\t    \tvar geom = JSON.parse(country.geom);
\t\t\t\t\t\t    \tgeom.properties.name = country.name;  \t
\t\t\t\t\t\t    \tgeom.properties.description = country.description;
\t\t\t\t\t\t    \tgeom.properties.color = country.color;
\t\t\t\t\t\t    \tgeom.properties.id = country.id;
\t\t\t\t\t\t    \tcartographie.countriesGeom.addData(geom);
\t\t\t\t\t\t    }
\t\t\t\t\t\t    
\t\t\t\t\t\t    cartographie.countriesList[country.id] = country;
\t\t\t\t\t    });
\t\t\t\t\t}
\t\t\t\t\t}).error(function() {});
\t\t\t},

\t\t\t// Chargement des fiefs
\t\t\t// Les geometries sont stocké dans les collections correspondates
\t\t\t// La liste des fiefs est mise à jour
\t\t\tloadFiefs: function() {
\t\t\t
\t\t\t\t\$.ajax({
\t\t\t\t\tdataType: \"json\",
\t\t\t\t\turl: \"world/fiefs.json\",
\t\t\t\t\tsuccess: function(data) {
\t\t\t\t\t    \$(data).each(function(key, country) {
\t\t\t\t\t\t    if ( country.geom != null )
\t\t\t\t\t\t    {
\t\t\t\t\t\t    \tvar geom = JSON.parse(country.geom);
\t\t\t\t\t\t    \tgeom.properties.name = country.name;  \t
\t\t\t\t\t\t    \tgeom.properties.description = country.description;
\t\t\t\t\t\t    \tgeom.properties.color = country.color;
\t\t\t\t\t\t    \tcartographie.fiefsGeom.addData(geom);
\t\t\t\t\t\t    }
\t
\t\t\t\t\t\t    cartographie.fiefsList[country.id] = country;
\t\t\t\t\t    });
\t\t\t\t\t}
\t\t\t\t\t}).error(function() {});
\t\t\t},

\t\t\t// sauvegarde une geometrie de territoire en base de donnee
\t\t\tsaveTerritoire: function() {
\t\t\t\tvar country = document.getElementById(\"selectedCountry\");
\t\t\t\tvar territoireId = country.options[country.selectedIndex].value;
\t\t\t\t\t
\t\t\t\t\$.ajax({
\t\t\t\t\ttype: \"POST\",
\t\t\t\t\tdataType: 'json',
\t\t\t\t\turl: \"world/countries/\"+territoireId+\"/update\",
\t\t\t\t\tdata: {geom: this.currentGeom, color: randomColor({luminosity: 'dark'}) },
\t\t\t\t\tsuccess: function(data) {
\t\t\t\t\t    alert(\"la geographie a été enregistrée\");
\t\t\t\t\t    var geom = JSON.parse(country.geom);
\t\t\t\t    \tgeom.properties.name = country.name;  \t
\t\t\t\t    \tgeom.properties.description = country.description;
\t\t\t\t\t    this.countriesGeom.addData(geom);
\t\t\t\t\t}
\t\t\t\t\t}).error(function() {
\t\t\t\t\t\talert(\"désolé, une erreur est survenue\");
\t\t\t\t\t});
\t\t\t\t
\t\t\t\tcartographie.saveTerritoirePanelDiv.innerHTML = '';
\t\t\t\t\$(cartographie.saveTerritoirePanelDiv).hide();
\t\t\t\tthis.currentGeom = null;
\t\t\t},

\t\t\t// sauvegarde une geometrie de fief en base de donnée
\t\t\tsaveFief: function() {
\t\t\t\tvar country = document.getElementById(\"selectedFief\");
\t\t\t\tvar territoireId = country.options[country.selectedIndex].value;
\t\t\t\t\t
\t\t\t\t\$.ajax({
\t\t\t\t\ttype: \"POST\",
\t\t\t\t\tdataType: 'json',
\t\t\t\t\turl: \"world/countries/\"+territoireId+\"/update\",
\t\t\t\t\tdata: {geom: this.currentGeom, color: randomColor({luminosity: 'light'})},
\t\t\t\t\tsuccess: function(data) {
\t\t\t\t\t    alert(\"la geographie a été enregistrée\");
\t\t\t\t\t    var geom = JSON.parse(country.geom);
\t\t\t\t    \tgeom.properties.name = country.name;  \t
\t\t\t\t    \tgeom.properties.description = country.description;
\t\t\t\t\t    this.fiefsGeom.addData(geom);
\t\t\t\t\t}
\t\t\t\t\t}).error(function() {
\t\t\t\t\t\talert(\"désolé, une erreur est survenue\");
\t\t\t\t\t});
\t\t\t\t
\t\t\t\tcartographie.saveFiefPanelDiv.innerHTML = '';
\t\t\t\t\$(cartographie.saveFiefPanelDiv).hide();
\t\t\t\tthis.currentGeom = null;
\t\t\t},

\t\t\t// Evenement création geometrie
\t\t\tdrawCreated: function(e) {
\t\t\t    var type = e.layerType,
\t\t        layer = e.layer;

\t\t\t    if (type === 'territoire') {
\t\t\t\t\tcartographie.displaySaveTerritoirePanel(JSON.stringify(layer.toGeoJSON()));
\t\t\t    }
\t\t\t    else if (type === 'fief') {
\t\t\t    \tcartographie.displaySaveFiefPanel(JSON.stringify(layer.toGeoJSON()));
\t\t\t    }
\t\t\t    else if (type === 'itineraire') {
\t\t\t\t    cartographie.displayItinerairePanel(layer);
\t\t\t    }
\t\t\t    else if (type === 'route') {
\t\t\t    \t//cartographie.displaySaveRoutePanel(JSON.stringify(layer.toGeoJSON()));
\t\t\t    }
\t\t\t    else if (type === 'fortification') {
\t\t\t    \t//cartographie.displaySaveFortificationPanel(JSON.stringify(layer.toGeoJSON()));
\t\t\t    }
\t\t\t\t
\t\t\t    layer.addTo(cartographie.drawnItems);
\t\t\t    cartographie.map.addLayer(layer);
\t\t\t},

\t\t\t// Evenement édition geometrie
\t\t\tdrawEdited: function(e) {
\t\t\t    var layers = e.layers;
\t\t\t    layers.eachLayer(function (layer) {
\t\t\t        //do whatever you want, most likely save back to db
\t\t\t        //cartographie.displaySaveTerritoirePanel(JSON.stringify(layer.toGeoJSON()));
\t\t\t    });
\t\t\t},

\t\t\t// Applique les geometries et les layers sur la carte
\t\t\tapplyOnMap: function() {
\t\t\t\tthis.countriesGeom.addTo(this.map);
\t\t\t\t

\t\t\t\t// Options disponibles dans le controlLayer
\t\t\t\tvar baseMaps = {
\t\t\t\t\t\"Vide\": this.workingTileLayer,
\t\t\t\t\t\"Base\" : this.baseTileLayer
\t\t\t\t};

\t\t\t\t// Options disponibles dans le controlLayer
\t\t\t\tvar overlayMaps = {
\t\t\t\t\t\"Pays\" : this.countriesGeom,
\t\t\t\t\t\"Fiefs\" : this.fiefsGeom
\t\t\t\t};

\t\t\t\t
\t\t\t\tL.control.layers(
\t\t\t\t\tbaseMaps, 
\t\t\t\t\toverlayMaps).addTo(this.map);
\t\t\t\t
\t\t\t\tL.control.mousePosition().addTo(this.map);

\t\t\t\tL.control.minimap(this.minimapLayer).addTo(this.map);

\t\t\t\tthis.map.keyboard.enable();\t

\t\t\t\tthis.map.addLayer(this.drawnItems);

\t\t\t\t
\t\t\t\t";
        // line 829
        if ($this->env->getExtension('security')->isGranted("ROLE_ORGA", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            // line 830
            echo "\t\t\t\t\tthis.map.addControl(this.drawControl);
\t\t\t\t";
        }
        // line 832
        echo "\t\t\t\t
\t\t\t\tthis.infoPanel.addTo(this.map);
\t\t\t\tthis.saveTerritoirePanel.addTo(this.map);
\t\t\t\tthis.saveFiefPanel.addTo(this.map);
\t\t\t\tthis.itinerairePanel.addTo(this.map);
\t\t\t\tthis.saveRoutePanel.addTo(this.map);
\t\t\t\tthis.saveFortificationPanel.addTo(this.map);

\t\t\t\tthis.map.on('draw:created', this.drawCreated);
\t\t\t\tthis.map.on('draw:edited', this.drawEdited);
\t\t\t}
\t\t\t
\t};

\tcartographie.createMap();
\tcartographie.createMapBounds();
\tcartographie.createWorkingTileLayer();
\tcartographie.createMinimapLayer();
\tcartographie.createBaseTileLayer();

\tcartographie.fitMap();
\t
\tcartographie.createGeomCollection();
\tcartographie.createInfoPanel();
\tcartographie.createDrawControl();
\tcartographie.createSaveTerritoirePanel();
\tcartographie.createSaveFiefPanel();
\tcartographie.createItinerairePanel();
\tcartographie.createSaveRoutePanel();
\tcartographie.createSaveFortificationPanel();
\tcartographie.loadCountries();
\tcartographie.loadFiefs();
\t
\tcartographie.applyOnMap();

</script>

";
    }

    public function getTemplateName()
    {
        return "public/world.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  915 => 832,  911 => 830,  909 => 829,  340 => 262,  336 => 261,  334 => 260,  327 => 256,  165 => 97,  161 => 96,  157 => 95,  153 => 94,  149 => 93,  144 => 90,  141 => 89,  136 => 86,  133 => 85,  57 => 12,  53 => 11,  49 => 10,  45 => 9,  40 => 6,  37 => 5,  31 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Le monde{% endblock title %}*/
/*        */
/* {% block style %}*/
/* */
/* 		<meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>*/
/* */
/* 	    <link rel="stylesheet" href="{{ app.request.basepath }}/leaflet/leaflet.css" />*/
/*         <link rel="stylesheet" href="{{ app.request.basepath }}/leaflet/L.Control.MousePosition.css" />*/
/*         <link rel="stylesheet" href="{{ app.request.basepath }}/leaflet/leaflet.draw.css" />*/
/*         <link rel="stylesheet" href="{{ app.request.basepath }}/leaflet/Control.MiniMap.min.css" />*/
/*         */
/* 		<style>		*/
/* 			html, body, #content {*/
/* 			    height: 100%;*/
/* 			    width: 100%;*/
/*   				overflow: hidden;*/
/* 			}*/
/* 			body {*/
/* 				padding-top: 50px;*/
/* 			}*/
/* 			*/
/* 			#map {*/
/* 				width: auto;*/
/*   				height: 100%;*/
/* 			}*/
/* 			.navbar {*/
/* 				margin-bottom:0px;*/
/* 				margin-top: -50px;*/
/* 			}*/
/* 			#content {*/
/* 				padding: 0 0 0 0;*/
/* 			}*/
/* 			*/
/* 			.info, .territoire, .fief, .itineraire, .route, .fortification {*/
/* 				padding: 6px 8px;*/
/* 				font: 14px/16px Arial, Helvetica, sans-serif;*/
/* 				background: white;*/
/* 				background: rgba(255,255,255,0.8);*/
/* 				box-shadow: 0 0 15px rgba(0,0,0,0.2);*/
/* 				border-radius: 5px;*/
/* 				width: 300px;*/
/* 			}*/
/* 			.info h4, .territoire h4, .fief h4, .itineraire h4, .route h4, .fortification h4 {*/
/* 				margin: 0 0 5px;*/
/* 				color: #777;*/
/* 			}*/
/* 			*/
/* 			.info {*/
/* 				width :500px;*/
/* 			}*/
/* 			*/
/* 			.geom {*/
/* 				display: none;*/
/* 			}*/
/* 			*/
/* 			.leaflet-draw-toolbar .leaflet-draw-draw-territoire {*/
/* 				background-position: -31px -2px;*/
/* 			}*/
/* 			*/
/* 			.leaflet-draw-toolbar .leaflet-draw-draw-fief {*/
/* 				background-position: -31px -2px;*/
/* 			}*/
/* 			*/
/* 			.leaflet-draw-toolbar .leaflet-draw-draw-route {*/
/* 				background-position: 0 -1px;*/
/* 			}*/
/* 			*/
/* 			.leaflet-draw-toolbar .leaflet-draw-draw-itineraire {*/
/* 				background-position: 0 -1px;*/
/* 			}*/
/* */
/* 			.leaflet-draw-toolbar .leaflet-draw-draw-fortification {*/
/* 				background-position: -122px -2px;*/
/* 			}*/
/* 			*/
/* 			.leaflet-touch .leaflet-draw-toolbar .leaflet-draw-draw-fortification {*/
/* 				background-position: -120px -1px;*/
/* 			}*/
/* */
/* 		</style>*/
/* {% endblock %}*/
/* */
/* {% block content %}*/
/* 		<div id="map"></div>*/
/* {% endblock %}*/
/* */
/* {% block javascript %}*/
/* */
/* */
/* */
/* <script src="{{ app.request.basepath }}/leaflet/leaflet.js"></script>*/
/* <script src="{{ app.request.basepath }}/leaflet/L.Control.MousePosition.js"></script>*/
/* <script src="{{ app.request.basepath }}/leaflet/leaflet.draw.js"></script>*/
/* <script src="{{ app.request.basepath }}/leaflet/Control.MiniMap.min.js"></script>*/
/* <script src="{{ app.request.basepath }}/js/randomColor.min.js"></script>*/
/* */
/* */
/* <script>*/
/* */
/* */
/* 	var cartographie = {*/
/* 			// Url du tileset*/
/* 			mapUrl: 'img/map/{z}/{x}/{y}.png',*/
/* */
/* 			mapCleanUrl: 'img/map_clean/{z}/{x}/{y}.png',*/
/* 			*/
/* 			// Zoom minimal*/
/* 			mapMinZoom: 0,*/
/* 				*/
/* 			// zoom maximal*/
/* 			mapMaxZoom : 6,*/
/* */
/* 			// zoom au chargement de la carte*/
/* 			mapNormalZoom: 2,*/
/* 			*/
/* 			// Liste des pays	*/
/* 			countriesList : Object(),*/
/* */
/* 			// Liste des fiefs  	*/
/* 			fiefsList : Object(),*/
/* */
/* 			// Collection des geometries des pays		*/
/* 			countriesGeom: null,*/
/* */
/* 			// Collection des geometries des fiefs*/
/* 			fiefsGeom: null,*/
/* */
/* 			// Tilelayer de base*/
/* 			baseTileLayer: null,*/
/* */
/* 			// Tilelayer de travail*/
/* 			workingTileLayer: null,*/
/* */
/* 			minimapLayer: null,*/
/* */
/* 			// carte*/
/* 			map: null,*/
/* */
/* 			// limites de la carte*/
/* 			mapBounds : null,	*/
/* */
/* 			// Panneau d'information*/
/* 			infoPanel : null,*/
/* 			infoPanelDiv: null,*/
/* */
/* 			// Panneau de sauvegarde d'une geometrie de territoire*/
/* 			saveTerritoirePanel : null,*/
/* 			saveTerritoirePanelDiv: null,*/
/* */
/* 			// Panneau de sauvegarde d'une geometrie de territoire*/
/* 			saveFiefPanel : null,*/
/* 			saveFiefPanelDiv: null,*/
/* */
/* 			// Panneau de sauvegarde d'une geometrie de territoire*/
/* 			saveRoutePanel : null,*/
/* 			saveRoutePanelDiv: null,*/
/* */
/* 			// Panneau de calcul de distance & itineraire*/
/* 			itinerairePanel: null,*/
/* 			itinerairePanelDiv: null,*/
/* */
/* 			// Panneau de sauvegarde d'une geometrie de territoire*/
/* 			saveFortificationPanel : null,*/
/* 			saveFortificationPanelDiv: null,						*/
/* */
/* 			// Geometrie courante*/
/* 			currentGeom : null,*/
/* */
/* 			// Collection des geometrie dessiné*/
/* 			drawnItems : null,*/
/* */
/* */
/* 			// Création de la carte*/
/* 			createMap: function() {*/
/* 				this.map = L.map('map', {*/
/* 					  maxZoom: this.mapMaxZoom,*/
/* 					  minZoom: this.mapMinZoom,*/
/* 					  crs: L.CRS.Simple,*/
/* 					  detectRetina: true*/
/* 					});*/
/* 			},*/
/* 			*/
/* 			// Création des limites de la carte*/
/* 			createMapBounds: function() {*/
/* 				this.mapBounds = new L.LatLngBounds(*/
/* 						this.map.unproject([0,11536], this.mapMaxZoom),*/
/* 						this.map.unproject([16384,0], this.mapMaxZoom)	    */
/* 					);*/
/* 				this.map.setMaxBounds(this.mapBounds);*/
/* */
/* 				var _mapCenter = this.map.unproject([16384/2, 11536/2], this.mapMaxZoom);*/
/* 				this.map.setView(_mapCenter, this.mapNormalZoom);*/
/* 			},*/
/* 			*/
/* 			// Création du tileLayer de base*/
/* 			createBaseTileLayer : function() {*/
/* 				*/
/* 				this.baseTileLayer = L.tileLayer(this.mapUrl, {*/
/* 					minZoom: this.mapMinZoom, */
/* 			        maxZoom: this.mapMaxZoom,*/
/* 			        bounds: this.mapBounds,*/
/* 			        attribution: 'Rendered with <a href="http://www.gdal.org/gdal2tiles.html">Gdal2Tile</a> | Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>',*/
/* 			        tms: false,*/
/* 			        continuousWorld: true,*/
/* 			        noWrap: false,*/
/* 			        tilesize:255,*/
/* 			        crs: L.CRS.Simple,*/
/* 			        detectRetina: false*/
/* 				}).addTo(this.map);*/
/* 			},*/
/* */
/* 			// Création du tileLayer de base*/
/* 			createWorkingTileLayer : function() {*/
/* 				*/
/* 				this.workingTileLayer = L.tileLayer(this.mapCleanUrl, {*/
/* 					minZoom: this.mapMinZoom, */
/* 			        maxZoom: this.mapMaxZoom,*/
/* 			        bounds: this.mapBounds,*/
/* 			        attribution: 'Rendered with <a href="http://www.gdal.org/gdal2tiles.html">Gdal2Tile</a> | Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>',*/
/* 			        tms: false,*/
/* 			        continuousWorld: true,*/
/* 			        noWrap: false,*/
/* 			        tilesize:255,*/
/* 			        crs: L.CRS.Simple,*/
/* 			        detectRetina: false*/
/* 				}).addTo(this.map);*/
/* 			},*/
/* */
/* 			createMinimapLayer : function() {*/
/* 				*/
/* 				this.minimapLayer = L.tileLayer(this.mapCleanUrl, {*/
/* 					minZoom: this.mapMinZoom, */
/* 			        maxZoom: this.mapMaxZoom,*/
/* 			        bounds: this.mapBounds,*/
/* 			        attribution: 'Rendered with <a href="http://www.gdal.org/gdal2tiles.html">Gdal2Tile</a> | Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>',*/
/* 			        tms: false,*/
/* 			        continuousWorld: true,*/
/* 			        noWrap: false,*/
/* 			        tilesize:255,*/
/* 			        crs: L.CRS.Simple,*/
/* 			        detectRetina: false*/
/* 				});*/
/* 			},*/
/* */
/* 			// Applique les limites de la carte à la carte*/
/* 			fitMap: function() {*/
/* 				this.map.fitBounds(this.mapBounds);*/
/* 			},*/
/* */
/* 			// méthode déclenchée pour toute nouvelle geometrie*/
/* 			// Permet de lier des événements à une géométrie*/
/* 			onEachFeature: function(feature, layer) {*/
/* 			    if (feature.properties ) {*/
/* 				    var editLink = "{{ app.request.basepath }}/territoire/"+feature.properties.id+"/update";*/
/* */
/* 			        layer.bindPopup(*/
/* 					        '<strong>'+feature.properties.name+'</strong>' */
/* 					        {% if is_granted('ROLE_ORGA', app.user) %}*/
/* 					        	+'<br /><a href="'+editLink+'">Modifier</a>'*/
/* 					        {% endif %});*/
/* 			        layer.on({*/
/* 				        mouseover: cartographie.mouseOver,*/
/* 					    mouseout: cartographie.mouseOut*/
/* 				    });*/
/* 			    }*/
/* 			},*/
/* 			*/
/* 			// evenement mouseover sur une geometrie*/
/* 			mouseOver: function(e) {*/
/* 				var layer = e.target;*/
/* 				//console.log(layer);*/
/* 				cartographie.displayInfoPanel(layer.feature.properties);*/
/* 				$(cartographie.infoPanelDiv).show();*/
/* 			},*/
/* 			*/
/* 			// evenement mouseout sur une geometrie*/
/* 			mouseOut: function(e) {*/
/* 				$(cartographie.infoPanelDiv).hide();*/
/* 			},*/
/* */
/* 			// Creation des controles de dessins*/
/* 			createDrawControl: function() {*/
/* */
/* 				L.Draw.Territoire = L.Draw.Polygon.extend({*/
/* 					 initialize: function (map, options) {*/
/* 						 L.Draw.Polygon.prototype.initialize.call(this, map, options);*/
/* 				          this.type = 'territoire';*/
/* 				      }*/
/* 				});*/
/* 				*/
/* 				L.Draw.Fief = L.Draw.Polygon.extend({*/
/* 					 initialize: function (map, options) {*/
/* 						 L.Draw.Polygon.prototype.initialize.call(this, map, options);*/
/* 				          this.type = 'fief';*/
/* 				      }*/
/* 				});*/
/* */
/* 				L.Draw.Itineraire = L.Draw.Polyline.extend({*/
/* 					 initialize: function (map, options) {*/
/* 						 L.Draw.Polyline.prototype.initialize.call(this, map, options);*/
/* 				         this.type = 'itineraire';*/
/* 				      }*/
/* 				});*/
/* 				*/
/* 				L.Draw.Route = L.Draw.Polyline.extend({*/
/* 					 initialize: function (map, options) {*/
/* 						 L.Draw.Polyline.prototype.initialize.call(this, map, options);*/
/* 				          this.type = 'route';*/
/* 				      }*/
/* 				});*/
/* 				*/
/* 				L.Draw.Fortification = L.Draw.Marker.extend({*/
/* 					 initialize: function (map, options) {*/
/* 						 L.Draw.Marker.prototype.initialize.call(this, map, options);*/
/* 				          this.type = 'fortification';*/
/* 				      }*/
/* 				});*/
/* 			*/
/* 				var FortificationMarker = L.Icon.extend({*/
/* 				    options: {*/
/* 				        shadowUrl: null,*/
/* 				        iconAnchor: new L.Point(12, 12),*/
/* 				        iconSize: new L.Point(32, 32),*/
/* 				        iconUrl: 'img/buildings.svg'*/
/* 				    }*/
/* 				});*/
/* */
/* 				*/
/* 				L.DrawToolbar.include({*/
/* 				    getModeHandlers: function (map) {*/
/* 				        return [*/
/* 				            {*/
/* 				                enabled: true,*/
/* 				                handler: new L.Draw.Territoire(map),*/
/* 				                title: 'Tracer un territoire'*/
/* 				            },*/
/* 				            {*/
/* 				                enabled: true,*/
/* 				                handler: new L.Draw.Fief(map),*/
/* 				                title: 'Tracer un fief'*/
/* 				            },*/
/* 				            {*/
/* 				                enabled: true,*/
/* 				                handler: new L.Draw.Itineraire(map, {*/
/* 				                	shapeOptions: {*/
/* 				                		weight: 6,*/
/* 				                		color: '#3d86c2',	*/
/* 				                		opacity: 0.8,*/
/* 				                	},*/
/* 					                showLength: false}),*/
/* 				                title: 'Calculer un itineraire'*/
/* 				            },*/
/* 				            {*/
/* 				                enabled: true,*/
/* 				                handler: new L.Draw.Route(map),*/
/* 				                title: 'Tracer une route commerciale'*/
/* 				            },*/
/* 				            {*/
/* 				                enabled: true,*/
/* 				                handler: new L.Draw.Fortification(map, {icon: new FortificationMarker}),*/
/* 				                title: 'Placer une fortification'*/
/* 				            }*/
/* 				        ];*/
/* 				    }*/
/* 				});*/
/* 				*/
/* 				this.drawControl = new L.Control.Draw({*/
/* 				    edit: {*/
/* 				        featureGroup: this.drawnItems*/
/* 				    },*/
/* 				    draw: {*/
/* 					    polygon: {*/
/* 					    	shapeOptions: {*/
/* 				                color: '#bada55'*/
/* 				            }*/
/* 						}*/
/* 				    }*/
/* 				});*/
/* 			},			*/
/* 			*/
/* 			// Création des collections de geometries*/
/* 			createGeomCollection: function() {*/
/* 				this.countriesGeom = new L.geoJson(false, {style: this.territoireStyle, onEachFeature: this.onEachFeature});*/
/* 				this.fiefsGeom = new L.geoJson(false, {style: this.fiefStyle, onEachFeature: this.onEachFeature});*/
/* 				this.routesGeom = new L.geoJson(false, {onEachFeature: this.onEachFeature});*/
/* 				this.fortificationsGeom = new L.geoJson(false, {onEachFeature: this.onEachFeature});*/
/* 				this.drawnItems = new L.FeatureGroup();*/
/* 			},*/
/* */
/* 			// style des territoires*/
/* 			territoireStyle: function(feature) {*/
/* 				if ( feature.properties.color == null) {*/
/* 					feature.properties.color = randomColor({luminosity:'dark'});*/
/* 				}*/
/* 				return {*/
/* 	                weight: 5,*/
/* 	                opacity: 1,*/
/* 	                color: feature.properties.color,*/
/* 	                dashArray: '10',*/
/* 	                fillOpacity: 0.2,*/
/* 	                fillColor: feature.properties.color*/
/* 	            };*/
/* 			},*/
/* 			*/
/* 			// style des fiefs*/
/* 			fiefStyle: function(feature){*/
/* 				if ( feature.properties.color == null) {*/
/* 					feature.properties.color = randomColor({luminosity:'dark'});*/
/* 				}*/
/* 				return {*/
/* 	                weight: 2,*/
/* 	                opacity: 1,*/
/* 	                color: feature.properties.color,*/
/* 	                dashArray: '3',*/
/* 	                fillOpacity: 0,*/
/* 	                fillColor: '#666666'*/
/* 	            };*/
/* 			},*/
/* */
/* 			// Création du panneau d'information*/
/* 			createInfoPanel: function() {*/
/* 				this.infoPanel = L.control();*/
/* 				this.infoPanel.onAdd = function(map) {*/
/* 					cartographie.infoPanelDiv = L.DomUtil.create('div', 'info');*/
/* 					//cartographie.infoPanelDiv.innerHTML = '';*/
/* 					$(cartographie.infoPanelDiv).hide();*/
/* 					return cartographie.infoPanelDiv;*/
/* 				};*/
/* 			},*/
/* */
/* 			displayInfoPanel: function(props)*/
/* 			{*/
/* 				if ( props && ! props.description ) props.description = 'Aucune description';*/
/* 				cartographie.infoPanelDiv.innerHTML = ('<h4>' + props.name + '</h4>'*/
/* 						+ '<p>' + props.description + '</p>');*/
/* 			},*/
/* */
/* 			// Création du pannel de sauvegarde d'une geometrie territoire*/
/* 			createSaveTerritoirePanel: function() {*/
/* 				this.saveTerritoirePanel = L.control();*/
/* 				this.saveTerritoirePanel.onAdd = function(map) {*/
/* 					cartographie.saveTerritoirePanelDiv = L.DomUtil.create('div', 'territoire');*/
/* 					cartographie.saveTerritoirePanelDiv.innerHTML = '';*/
/* 					$(cartographie.saveTerritoirePanelDiv).hide();*/
/* 					this.currentGeom = null;*/
/* 					return cartographie.saveTerritoirePanelDiv;*/
/* 				};*/
/* 			},*/
/* */
/* 			// Création du pannel de sauvegarde d'une geometrie fief*/
/* 			createSaveFiefPanel: function() {*/
/* 				this.saveFiefPanel = L.control();*/
/* 				this.saveFiefPanel.onAdd = function(map) {*/
/* 					cartographie.saveFiefPanelDiv = L.DomUtil.create('div', 'fief');*/
/* 					cartographie.saveFiefPanelDiv.innerHTML = '';*/
/* 					$(cartographie.saveFiefPanelDiv).hide();*/
/* 					this.currentGeom = null;*/
/* 					return cartographie.saveFiefPanelDiv;*/
/* 				};*/
/* 			},*/
/* */
/* 			// Création du panneau itineraire et distance*/
/* 			createItinerairePanel: function() {*/
/* 				this.itinerairePanel = L.control({'position':'bottomleft'});*/
/* 				this.itinerairePanel.onAdd = function(map) {*/
/* 					cartographie.itinerairePanelDiv = L.DomUtil.create('div','itineraire');*/
/* 					cartographie.itinerairePanelDiv.innerHTML = '';*/
/* 					$(cartographie.itinerairePanelDiv).hide();*/
/* 					this.currentGeom = null;*/
/* 					return cartographie.itinerairePanelDiv;*/
/* 				};*/
/* 			},*/
/* */
/* 			// Création du pannel de sauvegarde d'une geometrie route*/
/* 			createSaveRoutePanel: function() {*/
/* 				this.saveRoutePanel = L.control();*/
/* 				this.saveRoutePanel.onAdd = function(map) {*/
/* 					cartographie.saveRoutePanelDiv = L.DomUtil.create('div', 'route');*/
/* 					cartographie.saveRoutePanelDiv.innerHTML = '';*/
/* 					$(cartographie.saveRoutePanelDiv).hide();*/
/* 					this.currentGeom = null;*/
/* 					return cartographie.saveRoutePanelDiv;*/
/* 				};*/
/* 			},*/
/* */
/* 			// Création du pannel de sauvegarde d'une geometrie fortification*/
/* 			createSaveFortificationPanel: function() {*/
/* 				this.saveFortificationPanel = L.control();*/
/* 				this.saveFortificationPanel.onAdd = function(map) {*/
/* 					cartographie.saveFortificationPanelDiv = L.DomUtil.create('div', 'fortification');*/
/* 					cartographie.saveFortificationPanelDiv.innerHTML = '';*/
/* 					$(cartographie.saveFortificationPanelDiv).hide();*/
/* 					this.currentGeom = null;*/
/* 					return cartographie.saveFortificationPanelDiv;*/
/* 				};*/
/* 			},*/
/* 			*/
/* 			// Affiche le panneau de sauvegarde d'une geometrie territorie*/
/* 			displaySaveTerritoirePanel: function(geom) {*/
/* 					this.currentGeom = geom;*/
/* 					var select = '<label>Choisissez le territoire : </label>';*/
/* 					select = select + '<select id="selectedCountry">';*/
/* 					for(var key in this.countriesList)*/
/* 					{*/
/* 						select = select + '<option value="'+this.countriesList[key].id+'">'+this.countriesList[key].name+'</option>'*/
/* 					}*/
/* 					select = select +  '</select>';*/
/* 					select = select + '<input type="submit" value="Sauver" onclick="cartographie.saveTerritoire()"/>'*/
/* 					*/
/* 					cartographie.saveTerritoirePanelDiv.innerHTML = select;			*/
/* 					*/
/* 					$(cartographie.saveTerritoirePanelDiv).show();*/
/* 			},*/
/* */
/* 			// Affiche le panneau de sauvegarde d'une geometrie territorie*/
/* 			displaySaveFiefPanel: function(geom) {*/
/* 					this.currentGeom = geom;*/
/* 					var select = '<label>Choisissez le fief : </label>';*/
/* 					select = select + '<select id="selectedFief">';*/
/* 					for(var key in this.fiefsList)*/
/* 					{*/
/* 						select = select + '<option value="'+this.fiefsList[key].id+'">'+this.fiefsList[key].name+'</option>'*/
/* 					}*/
/* 					select = select +  '</select>';*/
/* 					select = select + '<input type="submit" value="Sauver" onclick="cartographie.saveFief()"/>'*/
/* 					*/
/* 					cartographie.saveFiefPanelDiv.innerHTML = select;			*/
/* 					*/
/* 					$(cartographie.saveFiefPanelDiv).show();*/
/* 			},*/
/* */
/* 			// calcul de la distance entre deux points*/
/* 			distance: function(latlngs) {*/
/* 				if ( latlngs.length <= 1) return 0;*/
/* 				var refPoint = latlngs[0];*/
/* 				var distanceTotal = 0;*/
/* 				*/
/* 				for (i=1; i< latlngs.length; i++) */
/* 				{*/
/* 					var Ay = (refPoint.lat *  -11536) / 180;*/
/* 					var Ax = (refPoint.lng * 16384) / 255;*/
/* */
/* 					var By = (latlngs[i].lat *  -11536) / 180;*/
/* 					var Bx = (latlngs[i].lng * 16384) / 255;*/
/* 					*/
/* 					var distance = Math.sqrt(Math.pow(Bx - Ax,2) + Math.pow(By - Ay,2));*/
/* 					*/
/* 					distanceTotal += distance;*/
/* 					refPoint = latlngs[i];*/
/* 				}*/
/* 				distanceTotal *= 1386.47; */
/* 				return distanceTotal;*/
/* 			},*/
/* */
/* 			// calcul le temps necessaire pour parcourir une distance en fonction du type de moyen de transport*/
/* 			calculTemps: function(distance, transport, heureParJour)*/
/* 			{*/
/* 				var heure = 0, jour = 0;*/
/* 				switch(transport) {*/
/* 					case "0": // pied 5km/heure*/
/* 						heure = Math.round(distance / 5);*/
/* 						break;*/
/* 					case "1": // cheval 15km/heure*/
/* 						heure = Math.round(distance / 15);*/
/* 						break;*/
/* 					case "2": // cavalerie normal 6.25Km/heure*/
/* 						heure = Math.round(distance / 6.25);*/
/* 						break;*/
/* 					case "3": // cavalerie forcée 9.4Km/heure*/
/* 						heure = Math.round(distance / 9.4);*/
/* 						break;*/
/* 					case "4": // cavalerie bagage 3km/heure*/
/* 						heure = Math.round(distance / 3);*/
/* 						break;*/
/* 					case "5": // infanterie normale 2.5km/heure*/
/* 						heure = Math.round(distance / 2.5);*/
/* 						break;*/
/* 					case "6": // infanterie forcée 5km/h*/
/* 						heure = Math.round(distance / 5);*/
/* 						break;*/
/* 				}*/
/* */
/* 				if ( heure > heureParJour ) {*/
/* 					jour = Math.round(heure / heureParJour);*/
/* 					heure = heure % heureParJour;*/
/* 				}*/
/* */
/* 				var text = '';*/
/* 				if ( jour > 0 ) text = jour+" jour(s) et ";*/
/* 				if ( heure == 0 ) text += "moins d'une heure";*/
/* 				else text += heure + " heures";*/
/* 				*/
/* 				$('#tempsVoyage').text(text);*/
/* 			},*/
/* 			*/
/* 			// Affiche le panneau de calcul du temps de voyage*/
/* 			displayItinerairePanel: function(layer)*/
/* 			{*/
/* 				var distance = this.distance(layer._latlngs);*/
/* 				distance = Math.round((distance / 1000)*100)/100;*/
/* 				var select = '<h4>Détail de votre itineraire</h4>';*/
/* 				select += '<strong>Distance :</strong><br /><span style="color:green">' + distance + ' Km'+'</span><br />';*/
/* 				select += '<label>Moyen de transport</label>'*/
/* 					+ '<select id="selectTempsVoyage">'*/
/* 					+ '<option value="0">Pied</option>'*/
/* 					+ '<option value="1">Cheval</option>'*/
/* 					+ '<option value="2">Cavalerie (Marche normale)</option>'*/
/* 					+ '<option value="3">Cavalerie (Marche forcée)</option>'*/
/* 					+ '<option value="4">Cavalerie (Bagages)</option>'*/
/* 					+ '<option value="5">Infanterie (Marche normale)</option>'*/
/* 					+ '<option value="6">Infanterie (Marche forcée)</option>'*/
/* 					+ '</select>'*/
/* 					+ '<label>Durée de la journée</label>'*/
/* 					+ '<input type="number" id="heureParJour" value="8" size="2"></input><br />'*/
/* 					+ '<strong>Durée du voyage</strong><br />'*/
/* 					+ '<div id="tempsVoyage" style="color:green;"></div>'*/
/* 					+ '<a href="#" onclick="cartographie.closeItinerairePanel()" style="float: right;">Fermer</a>';*/
/* 					*/
/* 				cartographie.itinerairePanelDiv.innerHTML = select;*/
/* 				$(cartographie.itinerairePanelDiv).show();*/
/* */
/* 				cartographie.calculTemps(distance, $("#selectTempsVoyage").val(), 8);*/
/* */
/* 				$('#heureParJour').change(function() {*/
/* 					var heureParJour = $(this).val();*/
/* 					cartographie.calculTemps(distance, $('#selectTempsVoyage').val(), heureParJour);*/
/* 				});*/
/* 				*/
/* 				$('#selectTempsVoyage').change(function() {*/
/* 					var heureParJour = $("#heureParJour").val();*/
/* 					cartographie.calculTemps(distance, $(this).val(), heureParJour);*/
/* 				});*/
/* 			},*/
/* */
/* 			// Ferme le panneau itineraire*/
/* 			closeItinerairePanel: function()*/
/* 			{*/
/* 				cartographie.itinerairePanelDiv.innerHtml = "";*/
/* 				$(cartographie.itinerairePanelDiv).hide();*/
/* 			},*/
/* 			*/
/* 			// Affiche le panneau de sauvegarde d'une geometrie territorie*/
/* 			displaySaveRoutePanel: function(geom) {*/
/* 					this.currentGeom = geom;*/
/* 					var select = '<p>Non disponible</p>';*/
/* 					cartographie.saveRoutePanelDiv.innerHTML = select;			*/
/* 					*/
/* 					$(cartographie.saveRoutePanelDiv).show();*/
/* 			},*/
/* */
/* 			// Affiche le panneau de sauvegarde d'une geometrie territorie*/
/* 			displaySaveFortificationPanel: function(geom) {*/
/* 				this.currentGeom = geom;*/
/* 				var select = '<p>Non disponible</p>';*/
/* 				cartographie.saveFortificationPanelDiv.innerHTML = select;			*/
/* 				*/
/* 				$(cartographie.saveFortificationPanelDiv).show();*/
/* 			},*/
/* */
/* 			// Chargement des pays*/
/* 			// Les geometries sont stocké dans les collections correspondates*/
/* 			// La liste des pays est mise à jour*/
/* 			loadCountries: function() {*/
/* 				$.ajax({*/
/* 					dataType: "json",*/
/* 					url: "world/countries.json",*/
/* 					success: function(data) {*/
/* 					    $(data).each(function(key, country) {*/
/* 						    if ( country.geom != null )*/
/* 						    {*/
/* 						    	var geom = JSON.parse(country.geom);*/
/* 						    	geom.properties.name = country.name;  	*/
/* 						    	geom.properties.description = country.description;*/
/* 						    	geom.properties.color = country.color;*/
/* 						    	geom.properties.id = country.id;*/
/* 						    	cartographie.countriesGeom.addData(geom);*/
/* 						    }*/
/* 						    */
/* 						    cartographie.countriesList[country.id] = country;*/
/* 					    });*/
/* 					}*/
/* 					}).error(function() {});*/
/* 			},*/
/* */
/* 			// Chargement des fiefs*/
/* 			// Les geometries sont stocké dans les collections correspondates*/
/* 			// La liste des fiefs est mise à jour*/
/* 			loadFiefs: function() {*/
/* 			*/
/* 				$.ajax({*/
/* 					dataType: "json",*/
/* 					url: "world/fiefs.json",*/
/* 					success: function(data) {*/
/* 					    $(data).each(function(key, country) {*/
/* 						    if ( country.geom != null )*/
/* 						    {*/
/* 						    	var geom = JSON.parse(country.geom);*/
/* 						    	geom.properties.name = country.name;  	*/
/* 						    	geom.properties.description = country.description;*/
/* 						    	geom.properties.color = country.color;*/
/* 						    	cartographie.fiefsGeom.addData(geom);*/
/* 						    }*/
/* 	*/
/* 						    cartographie.fiefsList[country.id] = country;*/
/* 					    });*/
/* 					}*/
/* 					}).error(function() {});*/
/* 			},*/
/* */
/* 			// sauvegarde une geometrie de territoire en base de donnee*/
/* 			saveTerritoire: function() {*/
/* 				var country = document.getElementById("selectedCountry");*/
/* 				var territoireId = country.options[country.selectedIndex].value;*/
/* 					*/
/* 				$.ajax({*/
/* 					type: "POST",*/
/* 					dataType: 'json',*/
/* 					url: "world/countries/"+territoireId+"/update",*/
/* 					data: {geom: this.currentGeom, color: randomColor({luminosity: 'dark'}) },*/
/* 					success: function(data) {*/
/* 					    alert("la geographie a été enregistrée");*/
/* 					    var geom = JSON.parse(country.geom);*/
/* 				    	geom.properties.name = country.name;  	*/
/* 				    	geom.properties.description = country.description;*/
/* 					    this.countriesGeom.addData(geom);*/
/* 					}*/
/* 					}).error(function() {*/
/* 						alert("désolé, une erreur est survenue");*/
/* 					});*/
/* 				*/
/* 				cartographie.saveTerritoirePanelDiv.innerHTML = '';*/
/* 				$(cartographie.saveTerritoirePanelDiv).hide();*/
/* 				this.currentGeom = null;*/
/* 			},*/
/* */
/* 			// sauvegarde une geometrie de fief en base de donnée*/
/* 			saveFief: function() {*/
/* 				var country = document.getElementById("selectedFief");*/
/* 				var territoireId = country.options[country.selectedIndex].value;*/
/* 					*/
/* 				$.ajax({*/
/* 					type: "POST",*/
/* 					dataType: 'json',*/
/* 					url: "world/countries/"+territoireId+"/update",*/
/* 					data: {geom: this.currentGeom, color: randomColor({luminosity: 'light'})},*/
/* 					success: function(data) {*/
/* 					    alert("la geographie a été enregistrée");*/
/* 					    var geom = JSON.parse(country.geom);*/
/* 				    	geom.properties.name = country.name;  	*/
/* 				    	geom.properties.description = country.description;*/
/* 					    this.fiefsGeom.addData(geom);*/
/* 					}*/
/* 					}).error(function() {*/
/* 						alert("désolé, une erreur est survenue");*/
/* 					});*/
/* 				*/
/* 				cartographie.saveFiefPanelDiv.innerHTML = '';*/
/* 				$(cartographie.saveFiefPanelDiv).hide();*/
/* 				this.currentGeom = null;*/
/* 			},*/
/* */
/* 			// Evenement création geometrie*/
/* 			drawCreated: function(e) {*/
/* 			    var type = e.layerType,*/
/* 		        layer = e.layer;*/
/* */
/* 			    if (type === 'territoire') {*/
/* 					cartographie.displaySaveTerritoirePanel(JSON.stringify(layer.toGeoJSON()));*/
/* 			    }*/
/* 			    else if (type === 'fief') {*/
/* 			    	cartographie.displaySaveFiefPanel(JSON.stringify(layer.toGeoJSON()));*/
/* 			    }*/
/* 			    else if (type === 'itineraire') {*/
/* 				    cartographie.displayItinerairePanel(layer);*/
/* 			    }*/
/* 			    else if (type === 'route') {*/
/* 			    	//cartographie.displaySaveRoutePanel(JSON.stringify(layer.toGeoJSON()));*/
/* 			    }*/
/* 			    else if (type === 'fortification') {*/
/* 			    	//cartographie.displaySaveFortificationPanel(JSON.stringify(layer.toGeoJSON()));*/
/* 			    }*/
/* 				*/
/* 			    layer.addTo(cartographie.drawnItems);*/
/* 			    cartographie.map.addLayer(layer);*/
/* 			},*/
/* */
/* 			// Evenement édition geometrie*/
/* 			drawEdited: function(e) {*/
/* 			    var layers = e.layers;*/
/* 			    layers.eachLayer(function (layer) {*/
/* 			        //do whatever you want, most likely save back to db*/
/* 			        //cartographie.displaySaveTerritoirePanel(JSON.stringify(layer.toGeoJSON()));*/
/* 			    });*/
/* 			},*/
/* */
/* 			// Applique les geometries et les layers sur la carte*/
/* 			applyOnMap: function() {*/
/* 				this.countriesGeom.addTo(this.map);*/
/* 				*/
/* */
/* 				// Options disponibles dans le controlLayer*/
/* 				var baseMaps = {*/
/* 					"Vide": this.workingTileLayer,*/
/* 					"Base" : this.baseTileLayer*/
/* 				};*/
/* */
/* 				// Options disponibles dans le controlLayer*/
/* 				var overlayMaps = {*/
/* 					"Pays" : this.countriesGeom,*/
/* 					"Fiefs" : this.fiefsGeom*/
/* 				};*/
/* */
/* 				*/
/* 				L.control.layers(*/
/* 					baseMaps, */
/* 					overlayMaps).addTo(this.map);*/
/* 				*/
/* 				L.control.mousePosition().addTo(this.map);*/
/* */
/* 				L.control.minimap(this.minimapLayer).addTo(this.map);*/
/* */
/* 				this.map.keyboard.enable();	*/
/* */
/* 				this.map.addLayer(this.drawnItems);*/
/* */
/* 				*/
/* 				{% if is_granted('ROLE_ORGA', app.user) %}*/
/* 					this.map.addControl(this.drawControl);*/
/* 				{% endif %}*/
/* 				*/
/* 				this.infoPanel.addTo(this.map);*/
/* 				this.saveTerritoirePanel.addTo(this.map);*/
/* 				this.saveFiefPanel.addTo(this.map);*/
/* 				this.itinerairePanel.addTo(this.map);*/
/* 				this.saveRoutePanel.addTo(this.map);*/
/* 				this.saveFortificationPanel.addTo(this.map);*/
/* */
/* 				this.map.on('draw:created', this.drawCreated);*/
/* 				this.map.on('draw:edited', this.drawEdited);*/
/* 			}*/
/* 			*/
/* 	};*/
/* */
/* 	cartographie.createMap();*/
/* 	cartographie.createMapBounds();*/
/* 	cartographie.createWorkingTileLayer();*/
/* 	cartographie.createMinimapLayer();*/
/* 	cartographie.createBaseTileLayer();*/
/* */
/* 	cartographie.fitMap();*/
/* 	*/
/* 	cartographie.createGeomCollection();*/
/* 	cartographie.createInfoPanel();*/
/* 	cartographie.createDrawControl();*/
/* 	cartographie.createSaveTerritoirePanel();*/
/* 	cartographie.createSaveFiefPanel();*/
/* 	cartographie.createItinerairePanel();*/
/* 	cartographie.createSaveRoutePanel();*/
/* 	cartographie.createSaveFortificationPanel();*/
/* 	cartographie.loadCountries();*/
/* 	cartographie.loadFiefs();*/
/* 	*/
/* 	cartographie.applyOnMap();*/
/* */
/* </script>*/
/* */
/* {% endblock %}*/
