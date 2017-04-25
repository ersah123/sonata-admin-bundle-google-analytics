Google Analytics API v4 Integration for Sonata Admin bundle
======================================

It is a simple package that uses Google Analytics Api v4 to give you all the information available by Dimensions & Metrics. It has default Sonata Admin Bundle 'list' functionality. Meaning, you can sort, search, date filter your Google Analytics report.
   
To be able to use it, you have to setup a project on Google Console for Google Analytics, get the json key, then configure this package by setting the path for it. You'll have to add the developer email defined into the Google Console to the GA views to authorize it, otherwise the view won't be accessible through the API.
  
  # Requirements
  
  SonataAdminBundle:
  
  https://sonata-project.org/bundles/admin/3-x/doc/getting_started/installation.html
  
  Add this lines to app/config/routing.yml
  
    _sonata_admin:
        resource: .
        type: sonata_admin
        prefix: /admin
  
  # installation

    composer require ersah/ga-bundle=dev-master

add to /app/AppKernel.php :

    $bundles = [
        ...
        new Ersah\GABundle\ErsahGABundle(),
    ];
    
  # configuration
  
      ersah_ga:
          view_id: ''
          google_analytics_json_key: "%kernel.root_dir%/../"
          limit: 15 # results per page
          from: "30daysAgo" # also YYYY-MM-DD
          to: "today"       # also YYYY-MM-DD
          sortBy: "ga:totalEvents" 
          sorting: "DESCENDING"
          list:
              dimensions:
                  - { value: 'ga:dimension1', label: 'Asset ID' } 
                  ...
              metrics:
                  - { value: 'ga:totalEvents', label: 'Total Events' }
                  ...
          #ga metrics and dimensions : 
 
 You can find available dimensions and metrics from here: 
 https://developers.google.com/analytics/devguides/reporting/core/dimsmets
 
Click check boxes items you want to use to see the possible combinations of dimensions and metrics.

  # Google Api Key
  
  Generate the json file from https://console.developers.google.com/start/api?id=analyticsreporting.googleapis.com&credential=client_key by creating a project, check the documentation : https://developers.google.com/analytics/devguides/reporting/core/v4/quickstart/service-php.
    
  Set relative path for your json key (set it on your server, better not into your repository) from execution path, ex:
  
  /app/config/config.yml
  
    ersah_ga:
        google_analytics_json_key: "%kernel.root_dir%/../" #root of the project
        
        
  You can see repost here: /admin/ga/list    
        
  # Google Analytics API v4
        
  Samples : https://developers.google.com/analytics/devguides/reporting/core/v4/samples

  # errors
        
  In that 403 error case, follow the link and authorize the API v4.
  
  # Creating API for same configuration
  
  Install FosRestBundle:
  
  http://symfony.com/doc/master/bundles/FOSRestBundle/1-setting_up_the_bundle.html
  
  Enable Serializer in /app/config/config.yml:
  
    framework:
        ...
        serializer: { enable_annotations: true }

  and: 
  
    fos_rest:
        param_fetcher_listener: true
        body_listener:          true
        format_listener:        true
        view:
            view_response_listener: force
        body_converter:
            enabled: false
            validate: true


  Add to app/config/routing.yml:
  
    ersah_api_ga:
        type:         rest
        prefix:       /api/ga
        resource:     "@ErsahGABundle/Controller/ApiController.php"



  Install JMSSerializerBundle:
  
  http://jmsyst.com/bundles/JMSSerializerBundle#installation
  
  Install NelmioApiDocBundle:
  
  http://symfony.com/doc/current/bundles/NelmioApiDocBundle/index.html
  
  You can see API endpoints and documentations in /api/doc
  
  # Bonus: Use Sonata Block Bundle for Main report
  
  In app/config/config.yml
  
    ersah_ga:
    ...
        main:
            metrics:
                - { value: 'ga:sessions', label: 'Sessions' }
                - { value: 'ga:bounceRate', label: 'Bounce Rate' }
                - { value: 'ga:avgTimeOnPage', label: 'Average Time On Page' }
                - { value: 'ga:pageviewsPerSession', label: 'Page Views Per Session' }
                - { value: 'ga:percentNewVisits', label: 'Percent New Visits' }
                - { value: 'ga:pageviews', label: 'Page Views' }
                - { value: 'ga:avgPageLoadTime', label: 'Average Page Load Time' }
  
  In app/config/config.yml
  
    sonata_admin:
        dashboard:
            blocks:
                -
                    position: left
                    type: sonata.admin.block.admin_list
                -
                    position: right
                    type: ersah.block.service.ga  
  
  You can so your GA block on your /admin/dashboard