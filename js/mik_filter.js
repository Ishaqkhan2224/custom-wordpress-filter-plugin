var mikFilter = new Vue({
  el: "#mikItemFilter",
  data: {
    postsData: [],
    locations: [],
    selectedLocations: [],
    issuesTreated: [],
    selectedIssuesTreated: [],
    gender: [],
    selectedGender: [],
    ageSpeciality: [],
    selectedAgeSpeciality: [],
    professionalRoles: [],
    selectedProfessionalRoles: [],
    current: 1,
    posts: 200,
  },
  computed: {
    hasClinicians() {
      return this.filteredPosts.some(
        (post) =>
          post.professional_role[0] &&
          post.professional_role[0].slug === "clinicians"
      );
    },
    hasInterns() {
      return this.filteredPosts.some(
        (post) =>
          post.professional_role[0] &&
          post.professional_role[0].slug === "interns"
      );
    },
    hasConsultants() {
      return this.filteredPosts.some(
        (post) =>
          post.professional_role[0] &&
          post.professional_role[0].slug === "consultants"
      );
    },

    slicedPosts: function () {
      var self = this;
      return self.filteredPosts.slice(
        (this.current - 1) * this.posts,
        this.current * this.posts
      );
    },
    pagination: function () {
      return Math.ceil(this.filteredPosts.length / this.posts);
    },
    filteredPosts: function () {
      var self = this;
      var location_found,
        issues_treated_found,
        gender_found,
        age_speciality_found;
      return self.postsData.filter(function (item) {
        // Locations Filter
        if (self.selectedLocations.length !== 0) {
          var count = 0;
          // Check if item.clinician_locations is an array
          if (Array.isArray(item.clinician_locations)) {
            item.clinician_locations.forEach(function (location_term) {
              console.log("postDataLocation", location_term.name);
              self.selectedLocations.forEach(function (selectedLocationItem) {
                console.log("selectedLocations", selectedLocationItem);
                if (location_term.name === selectedLocationItem) {
                  count++;
                }
              });
            });
          } else {
            location_found = false;
          }
          location_found = count > 0 ? true : false;
        } else {
          location_found = true;
        }

        // Gender Filter
        if (self.selectedGender.length !== 0) {
          var genderCount = 0;
          if (Array.isArray(item.clinician_gender)) {
            item.clinician_gender.forEach(function (tax_term) {
              self.selectedGender.forEach(function (selectedItem) {
                if (tax_term.name === selectedItem) {
                  genderCount++;
                }
              });
            });
          } else {
            gender_found = false;
          }
          gender_found = genderCount > 0 ? true : false;
        } else {
          gender_found = true;
        }

        // Issues Treated Filter
        if (self.selectedIssuesTreated.length !== 0) {
          var issuesCount = 0;
          if (Array.isArray(item.issues_treated)) {
            item.issues_treated.forEach(function (tax_term) {
              self.selectedIssuesTreated.forEach(function (selectedItem) {
                if (tax_term.name === selectedItem) {
                  issuesCount++;
                }
              });
            });
          } else {
            issues_treated_found = false;
          }
          issues_treated_found = issuesCount > 0 ? true : false;
        } else {
          issues_treated_found = true;
        }

        // Age Speciality Filter
        if (self.selectedAgeSpeciality.length !== 0) {
          var ageSpecialityCount = 0;
          if (Array.isArray(item.age_speciality)) {
            item.age_speciality.forEach(function (tax_term) {
              self.selectedAgeSpeciality.forEach(function (selectedItem) {
                if (tax_term.name === selectedItem) {
                  ageSpecialityCount++;
                }
              });
            });
          } else {
            age_speciality_found = false;
          }
          age_speciality_found = ageSpecialityCount > 0 ? true : false;
        } else {
          age_speciality_found = true;
        }

        return (
          location_found &&
          issues_treated_found &&
          gender_found &&
          age_speciality_found
        );
      });
    },
  },
  methods: {
    getTaxonomy: function (e, term, tax) {
      var self = this;
      e.preventDefault();
      // jQuery(".filter-loader").addClass("active");
      if (tax === "locations") {
        var index = self.selectedLocations.indexOf(term);
        if (index > -1) {
          self.selectedLocations.splice(index, 1);
        } else {
          self.selectedLocations.push(term);
        }
      }

      if (tax === "issues treated") {
        var index = self.selectedIssuesTreated.indexOf(term);
        if (index > -1) {
          self.selectedIssuesTreated.splice(index, 1);
        } else {
          self.selectedIssuesTreated.push(term);
        }
      }

      if (tax === "gender") {
        var index = self.selectedGender.indexOf(term);
        if (index > -1) {
          self.selectedGender.splice(index, 1);
        } else {
          self.selectedGender.push(term);
        }
      }

      if (tax === "age speciality") {
        var index = self.selectedAgeSpeciality.indexOf(term);
        if (index > -1) {
          self.selectedAgeSpeciality.splice(index, 1);
        } else {
          self.selectedAgeSpeciality.push(term);
        }
      }

      this.current = 1;
    },
    clearFilter: function (e) {
      e.preventDefault();
      location.reload();
    },
    nextPage: function () {
      var totalpages = this.pagination;
      if (this.current < totalpages) {
        this.current += 1;

        // Check if the target element exists before scrolling
        var targetElement = document.querySelector("#mikItemFilter");
        if (targetElement) {
          targetElement.scrollIntoView({
            behavior: "smooth",
            block: "start",
          });
        }
      }
    },
    prevPage: function () {
      if (this.current > 1) {
        this.current -= 1;

        // Check if the target element exists before scrolling
        var targetElement = document.querySelector("#mikItemFilter");
        if (targetElement) {
          targetElement.scrollIntoView({
            behavior: "smooth",
            block: "start",
          });
        }
      }
    },

    setCurrentPage: function (c, e) {
      var totalpages = this.pagination;
      jQuery(e.target).siblings("span").removeClass("active");
      jQuery(e.target).addClass("active");
      this.current = c;
      var targetElement = document.querySelector("#mikItemFilter");
      if (targetElement) {
        targetElement.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    },
    shouldPageShow: function (c) {
      var totalpages = this.pagination;
      //                    if(totalpages > 7){
      //                        if(c <= 4 || c > totalpages - 3 ){
      //                            return true;
      //                        }else{
      //                            return false;
      //                        }
      //                    }
      return true;
    },
    getCount: function (c) {
      var totalpages = this.pagination;
      //                    if(totalpages > 7){
      //                        if(c >= 4 && c < totalpages - 3 ){
      //                            return '...';
      //                        }
      //                    }
      return c;
    },
  },
  mounted: function () {
    var self = this;
    jQuery(".filter-loader").addClass("active");

    jQuery.ajax({
      url: mikObj.ajaxurl,
      type: "POST",
      dataType: "JSON",
      data: {
        action: "get_posts_data",
      },
      success: function (res) {
        jQuery(".filter-loader").removeClass("active");
        self.postsData = res.cliniciansData;

        self.locations = res.locations;
        self.issuesTreated = res.issues_treated;
        self.gender = res.gender;
        self.ageSpeciality = res.age_speciality;
        self.professionalRoles = res.professional_roles;
      },
      error: function (res) {
        console.log("Error");
      },
    });
  },
});

jQuery(document).ready(function () {
  if (jQuery(window).width() > 768) {
    // Add active class on hover
    jQuery("#menu-main >li").hover(
      function () {
        jQuery(this).addClass("active");
      },
      function () {
        jQuery(this).removeClass("active");
      }
    );

    // Remove active class when mouse leaves submenu
    jQuery("#menu-main >li").mouseleave(function () {
      jQuery(this).removeClass("active");
    });
  } else {
    jQuery(document).on("click", "#menu-main >li", function (e) {
      if (jQuery(this).hasClass("active")) {
        jQuery(this).removeClass("active");
      } else {
        jQuery(this).addClass("active");
      }
    });
  }
});
