define(['jquery'], function($) {
    'use strict';

    return {
        // Initialize the Facebook SDK with the provided app ID by Webshop owners in the configuration
        init: function(appId) {
            // This function is called once the SDK is loaded
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : appId,  // Facebook App ID
                    cookie     : true,   // Enable cookies to allow the server to access the session
                    xfbml      : true,   // Parse social plugins on this webpage
                    version    : 'v16.0' // Use this Graph API version
                });
            };

            // Load the Facebook SDK asynchronously - Create script and append in the head
            (function(){
              if (document.getElementById('facebook-jssdk')) return; // Prevent loading the SDK multiple times
              var script = document.createElement('script');
              script.id = 'facebook-jssdk'; 
              script.src = 'https://connect.facebook.net/en_US/sdk.js'; 
              document.getElementsByTagName('head')[0].appendChild(script); 
            }());

            // Bind click event to login button
            $('#loginBtn').on('click', function() {
                FB.login(function(response) { // Call FB.login to authenticate user
                    if (response.authResponse) { // Check if login was successful
                        console.log('Welcome! Fetching your information...');
                        this.fetchUserData(); // Fetch user data on successful login
                    } else {
                        console.log('User cancelled login or did not fully authorize.'); // Handle login cancellation
                    }
                }.bind(this), {scope: 'public_profile,email,user_friends,user_posts,user_photos,user_videos,user_events,user_likes,user_birthday,user_hometown,user_location,user_link'}); // Request permissions for user data
            }.bind(this));
        },

        // Fetch user data from Facebook API
        fetchUserData: function() {
            FB.api('/me', { // Call the Graph API to get user data
                fields: 'id,first_name,last_name,name,email,picture.width(2048).height(2048),friends,posts{message,created_time},photos,videos,events,likes,birthday,hometown,location{location{country_code, city}},link,gender,languages,short_name,favorite_teams' // Specified fields from requirements
            }, function(response) {
                console.log('Retrieved user data:', response);
                this.saveUserData(response); // Save user data to server (Session storage via ajax)
            }.bind(this));
        },

        // Save user data to server via AJAX request
        saveUserData: function(response) {
            $.ajax({
                url: '/facebook/oauth/saveUserData', // Endpoint to save user data on the server
                method: 'POST', 
                data: { 
                    id: response.id,
                    firstname: response.first_name,
                    lastname: response.last_name,
                    name: response.name,
                    email: response.email,
                    profile_picture_url: response.picture.data.url,
                    gender: response.gender,
                    birthday: response.birthday,
                    hometown: response.hometown.name,
                    likes: response.likes ? response.likes : null,
                    location: response.location.location.city,
                    country: response.location.location.country_code,
                    friends: response.friends ? response.friends.data : null,
                    posts: response.posts ? response.posts.data : null ,
                    languages: response.languages,
                    favorite_teams: response.favorite_teams


                },
                success: function(response) {
                    console.log('Data successfully saved.');
                },
                error: function(xhr, status, error) {
                    console.error('Error saving data: ', error); 
                }
            });
        }
    };
});
