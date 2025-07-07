<?php

/**
 * Plugin Name: MIK Clinicians Filter
 * Author: Muhammd Ishaq Khan
 * Version: 1.0
 * License: GPLv2 or later
 * Description: custom Posts Filter plugin
 */

defined('ABSPATH') || exit;

/**
 * WC_Admin_Importers Class.
 */
class WP_CUSTOM_CLINICIANS_FILTER
{

    public static $directory_path;
    public static $directory_url;
    public static $plugin_basename = ''; // Values set at function `set_plugin_vars`

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->set_plugin_vars();
        $this->hooks();
        $this->include_shorcodes();
        $this->register_styles();
    }

    /**
     * Include Hooks.
     */
    public function hooks()
    {
        //hooks
        add_action('wp_ajax_get_posts_data', array($this, 'get_posts_data'));
        add_action('wp_ajax_nopriv_get_posts_data', array($this, 'get_posts_data'));
    }

    /**
     * Define plugin variables.
     */
    public function set_plugin_vars()
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        self::$directory_path = plugin_dir_path(__FILE__);
        self::$directory_url = plugin_dir_url(__FILE__);
        self::$plugin_basename = plugin_basename(__FILE__);
    }

    /**
     * Include Shortcodes.
     */
    public function include_shorcodes()
    {
        //Shortcode
        add_shortcode('mik_clinicians_filter', array($this, 'mik_filter_shortcode'));

    }


    /**
     * Register_styles.
     */
    public function register_styles()
    {
        // files
        wp_register_style('mik-filter-css', self::$directory_url . 'css/mik_filter.css', array(),  microtime());
        wp_register_style('bootstrap-cpt', 'https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css');
        wp_register_style('flatpickr-css', self::$directory_url . 'css/flatpickr.min.css');


        wp_register_script('popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', array('jquery'), null, true);
//        wp_register_script('popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array('jquery'), null, true);
        wp_register_script('mik-filter-js', self::$directory_url . 'js/mik_filter.js', array('jquery'), microtime(), true);
        wp_register_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js', array('jquery'), null, true);
//        wp_register_script('bootstrap-js', self::$directory_url . 'js/bootstrap-dropdown.min.js', array('jquery'), null, true);
        wp_register_script('flatpickr-js', self::$directory_url . 'js/flatpickr.min.js', array('jquery'), null, true);


        wp_register_script('vue-js', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', array('jquery'), null, true);


    }


    /**
     * Enqueue_Files.
     */
    public function enqueue_files()
    {
        // files
        wp_enqueue_style('flatpickr-css');
        wp_enqueue_style('mik-filter-css');
//        wp_enqueue_style('bootstrap-cpt');


        wp_enqueue_script('popper');
        wp_enqueue_script('bootstrap-js');
//        wp_enqueue_script('flatpickr-js');

        wp_enqueue_script('vue-js');
        wp_enqueue_script('mik-filter-js');

        $localize = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        );
        wp_localize_script('mik-filter-js', 'mikObj', $localize);

    }


    function mik_filter_shortcode($atts)
    {

        $this->enqueue_files();
//        $page = isset($atts["search_page"]) ? true : false;

        ob_start();
        ?>

        <div id="mikItemFilter" v-cloak="">
            <div class="filter-loader active"><img src="https://www.pillarstherapy.com/wp-content/themes/pillars/images/load.gif" alt="loader"></div>
            <div class="select-fields-wrapper">
                <nav class="top_menu">
                    <ul id="menu-main" class="menu">
                        <li class="menu-item">
                            <a href="#">Locations</a>
                            <ul class="sub-menu">
                                <li
                                    v-for="(loc,indx) in locations" :key="indx"
                                    class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                                    <div class="custom-control custom-checkbox">
                                        <input @change="getTaxonomy($event,loc.name,'locations')" type="checkbox" class="custom-control-input" :id="loc.name+'-'+indx">
                                        <label class="custom-control-label" :for="loc.name+'-'+indx">{{ loc.name}}</label>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-item">
                            <a href="#">Gender</a>
                            <ul class="sub-menu">
                                <li
                                    v-for="(gen,indx) in gender" :key="indx"
                                    class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                                    <div class="custom-control custom-checkbox">
                                        <input @change="getTaxonomy($event,gen.name,'gender')" type="checkbox" class="custom-control-input" :id="gen.name+'-'+indx">
                                        <label class="custom-control-label" :for="gen.name+'-'+indx">
                                            {{ gen.name}}
<!--                                            <span v-if="selectedLocations.length < 1" class="ml-1">({{gen.count }})</span>-->
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-item">
                            <a href="#">Issues Treated</a>
                            <ul class="sub-menu">
                                <li
                                    v-for="(issue,indx) in issuesTreated" :key="indx"
                                    class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                                    <div class="custom-control custom-checkbox">
                                        <input @change="getTaxonomy($event,issue.name,'issues treated')" type="checkbox" class="custom-control-input" :id="issue.name+'-'+indx">
                                        <label class="custom-control-label" :for="issue.name+'-'+indx">
                                            {{ issue.name}}
<!--                                            <span class="ml-1">({{issue.count}})</span>-->
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-item">
                            <a href="#">Age Speciality</a>
                            <ul class="sub-menu">
                                <li
                                    v-for="(age,indx) in ageSpeciality" :key="indx"
                                    class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                                    <div class="custom-control custom-checkbox">
                                        <input @change="getTaxonomy($event,age.name,'age speciality')" type="checkbox" class="custom-control-input" :id="age.name+'-'+indx">
                                        <label class="custom-control-label" :for="age.name+'-'+indx">
                                            {{ age.name}}
<!--                                            <span class="ml-1">({{age.count}})</span>-->
                                        </label>

                                    </div>
                                </li>
                            </ul>
                        </li>

                        <a href="#" @click="clearFilter($event)" class="clear-filter-btn">Clear Filter</a>

                    </ul>
                </nav>
            </div>
            <template v-if="hasClinicians">
                <div v-for="(post,index) in slicedPosts" v-if="post.professional_role[0].slug == 'clinicians'" :key="index" class="clincian-wrap col-xs-12 col-sm-6">

                    <div class="clinician-block">
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-0"
                             style="padding-left: 0;">
                            <a :href="post.url">
                                <img :src="post.image" alt="">
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <h4>{{post.title}}</h4>
                            <p style="font-size: 14px;">
                                <i class="far fa-map-marker-alt"></i>
                                <template v-for="(loc, index) in post.clinician_locations">
                                    {{ loc.name }}<template v-if="index < post.clinician_locations.length - 1">, </template>
                                </template>
                            </p>

                            <p>{{post.bio}}</p>
                            <a :href="post.url">Read more</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </template>

            <template v-if="hasInterns">
                <h2 style="text-align: center;"><strong>Intern Therapists</strong></h2>
                <p>&nbsp;</p>
                <div v-for="(post,jnd) in slicedPosts" v-if="post.professional_role[0].slug == 'interns'" :key="jnd" class="clincian-wrap col-xs-12 col-sm-6">

                    <div class="clinician-block">
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-0"
                             style="padding-left: 0;">
                            <a :href="post.url">
                                <img :src="post.image" alt="">
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <h4>{{post.title}}</h4>
                            <p style="font-size: 14px;">
                                <i class="far fa-map-marker-alt"></i>
                                <template v-for="(loc, index) in post.clinician_locations">
                                    {{ loc.name }}<template v-if="index < post.clinician_locations.length - 1">, </template>
                                </template>
                            </p>

                            <p>{{post.bio}}</p>
                            <a :href="post.url">Read more</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </template>
            <template v-if="hasConsultants">
                <h2 style="text-align: center;"><strong>CONSULTANTS</strong></h2>
                <p>&nbsp;</p>
                <div v-for="(post,jnd) in slicedPosts" v-if="post.professional_role[0].slug == 'consultants'" :key="jnd" class="clincian-wrap col-xs-12 col-sm-6">

                    <div class="clinician-block">
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-0"
                             style="padding-left: 0;">
                            <a :href="post.url">
                                <img :src="post.image" alt="">
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <h4>{{post.title}}</h4>
                            <p style="font-size: 14px;">
                                <i class="far fa-map-marker-alt"></i>
                                <template v-for="(loc, index) in post.clinician_locations">
                                    {{ loc.name }}<template v-if="index < post.clinician_locations.length - 1">, </template>
                                </template>
                            </p>

                            <p>{{post.bio}}</p>
                            <a :href="post.url">Read more</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <p>&nbsp;</p>
                <div class="clear"></div>
            </template>

            <div v-if="slicedPosts.length == 0" class="no-result text-center"><h2>No result found!</h2></div>
        </div>


        <?php
        return ob_get_clean();
    }


    /**
     * Helper Functions
     */
    function get_posts_data() {
        $cliniciansData = [];
        $locations = [];
        $issuesTreated = [];
        $genders = [];
        $ageSpeciality = [];
        $professionalRoles = [];
        $args = array(
            'post_type' => 'clinician',
            'posts_per_page' => -1,
        );
        $loop = new WP_Query($args);
        while ($loop->have_posts()) {
            $loop->the_post();
            $post_id = get_the_ID();
            $url= get_field('clinician_link');
            $bio= get_field('bio_excerpt');

            $bio = wp_trim_words($bio, 24);

            $bio = html_entity_decode($bio);

            // Get the taxonomies assigned to the post
            $assignedIssues_treated= get_the_terms($post_id, 'issues_treated');
            $assignedLocations= get_the_terms($post_id, 'clinician_location');
            $assignedGender= get_the_terms($post_id, 'clinician_gender');
            $assignedAge_speciality= get_the_terms($post_id, 'age_specialty');
            $assignedRole= get_the_terms($post_id, 'professional_roles');
            $thumbnail_id = get_post_thumbnail_id($post_id);
            $image_url = wp_get_attachment_image_url($thumbnail_id, 'full');
            $associative = array(
                'id' => $post_id,
                'url' => $url,
                'image' => $image_url,
                'title' => html_entity_decode(get_the_title()),
                'bio' => $bio,
                'issues_treated' => $assignedIssues_treated,
                'clinician_locations' => $assignedLocations,
                'clinician_gender' => $assignedGender,
                'age_speciality' => $assignedAge_speciality,
                'professional_role' => $assignedRole,
            );
            array_push($cliniciansData, $associative);
        }

        wp_reset_postdata();

        // GET taxonomies clinician_locations
        $locationsArray = get_terms(array(
            'taxonomy' => 'clinician_location',
            'hide_empty' => true,
        ));
        foreach ($locationsArray as $location) {
            $location_object = array(
                'name' => html_entity_decode($location->name),
                'id' => $location->term_id,
                'count' => $location->count,
                'slug' => $location->slug,
            );
            $locations[] = $location_object;
        }

        // GET taxonomies issues_treated
        $issuesTreatedArray = get_terms(array(
            'taxonomy' => 'issues_treated',
            'hide_empty' => true,
        ));
        foreach ($issuesTreatedArray as $issuesTreatedTerm) { // Changed variable name here
            $issuesTreated_object = array(
                'name' => html_entity_decode($issuesTreatedTerm->name), // Changed variable name here
                'id' => $issuesTreatedTerm->term_id, // Changed variable name here
                'count' => $issuesTreatedTerm->count, // Changed variable name here
                'slug' => $issuesTreatedTerm->slug, // Changed variable name here
            );
            $issuesTreated[] = $issuesTreated_object;
        }

        // GET taxonomies clinician_gender
        $clinicianGenderArray = get_terms(array(
            'taxonomy' => 'clinician_gender',
            'hide_empty' => true,
        ));
        foreach ($clinicianGenderArray as $gender) {
            $clinicianGender_object = array(
                'name' => html_entity_decode($gender->name),
                'id' => $gender->term_id,
                'count' => $gender->count,
                'slug' => $gender->slug,
            );
            $genders[] = $clinicianGender_object;
        }


        // GET taxonomies age_specialty
        $ageSpecialtyArray = get_terms(array(
            'taxonomy' => 'age_specialty',
            'hide_empty' => true,
        ));
        foreach ($ageSpecialtyArray as $ageSpecialty) {
            $ageSpecialty_object = array(
                'name' => html_entity_decode($ageSpecialty->name),
                'id' => $ageSpecialty->term_id,
                'count' => $ageSpecialty->count,
                'slug' => $ageSpecialty->slug,
            );
            $ageSpeciality[] = $ageSpecialty_object;
        }

        // GET taxonomies professional_roles
        $professionalRolesArray = get_terms(array(
            'taxonomy' => 'professional_roles',
            'hide_empty' => true,
        ));
        foreach ($professionalRolesArray as $professionalRole) {
            $professionalRole_object = array(
                'name' => html_entity_decode($professionalRole->name),
                'id' => $professionalRole->term_id,
                'count' => $professionalRole->count,
                'slug' => $professionalRole->slug,
            );
            $professionalRoles[] = $professionalRole_object;
        }


        echo json_encode(array(
            'locations' => $locations,
            'issues_treated' => $issuesTreated,
            'gender' => $genders,
            'age_speciality' => $ageSpeciality,
            'professional_roles' => $professionalRoles,
            'cliniciansData' => $cliniciansData,
        ));
        wp_die();
    }

    function get_sub_categories($parent_id)
    {
        $sub_categories = get_terms(array(
            'taxonomy' => 'category',
            'hide_empty' => false,
            'parent' => $parent_id
        ));

        $result = array();

        foreach ($sub_categories as $sub_category) {
            $sub_category_object = array(
                'name' => html_entity_decode($sub_category->name),
                'id' => $sub_category->term_id,
                'count' => $sub_category->count,
                'slug' => $sub_category->slug,
                'subCategories' => $this->get_sub_categories($sub_category->term_id)
            );

            $result[] = $sub_category_object;
        }

        return $result;
    }


}

new WP_CUSTOM_CLINICIANS_FILTER();


?>