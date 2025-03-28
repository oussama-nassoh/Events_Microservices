<?php

use Dedoc\Scramble\Http\Middleware\RestrictedDocsAccess;

return [
    /*
     * Your API path. By default, all routes starting with this path will be added to the docs.
     * If you need to change this behavior, you can add your custom routes resolver using `Scramble::routes()`.
     */
    'api_path' => 'api',

    /*
     * Your API domain. By default, app domain is used. This is also a part of the default API routes
     * matcher, so when implementing your own, make sure you use this config if needed.
     */
    'api_domain' => null,

    /*
     * The path where your OpenAPI specification will be exported.
     */
    'export_path' => 'api.json',

    'info' => [
        /*
         * API version.
         */
        'version' => env('API_VERSION', '0.0.1'),

        /*
         * Description rendered on the home page of the API documentation (`/docs/api`).
         */
        'description' => '# Event Management Service API

The Event Service is a core component of our ticketing platform, providing comprehensive event management capabilities.

## Core Features

- **Event Management**:
  - Create and publish events
  - Update event details
  - Manage event capacity
  - Track ticket availability
  - Handle event scheduling

- **Event Types Support**:
  - Conferences and Seminars
  - Workshops and Training
  - Corporate Events
  - Tech Meetups
  - Professional Development Sessions

- **Event Components**:
  - Detailed event information
  - Speaker management
  - Sponsor integration
  - Location details
  - Pricing configuration
  - Image and media handling

## Event Statuses

Events can be in one of the following states:
- `draft`: Initial creation state
- `published`: Visible to users
- `cancelled`: Event cancelled
- `completed`: Past events

## API Endpoints

The service provides RESTful endpoints for:
- Event CRUD operations
- Event status management
- Capacity and ticket availability
- Event search and filtering
- Speaker and sponsor management
- Event analytics and reporting',
    ],

    /*
     * Customize Stoplight Elements UI
     */
    'ui' => [
        /*
         * Define the title of the documentation\'s website. App name is used when this config is `null`.
         */
        'title' => 'Event Management Service API Documentation',

        /*
         * Define the theme of the documentation. Available options are `light` and `dark`.
         */
        'theme' => 'light',

        /*
         * Hide the `Try It` feature. Enabled by default.
         */
        'hide_try_it' => false,

        /*
         * Hide the schemas in the Table of Contents. Enabled by default.
         */
        'hide_schemas' => false,

        /*
         * URL to an image that displays as a small square logo next to the title, above the table of contents.
         */
        'logo' => '',

        /*
         * Use to fetch the credential policy for the Try It feature. Options are: omit, include (default), and same-origin
         */
        'try_it_credentials_policy' => 'include',
    ],

    /*
     * The list of servers of the API. By default, when `null`, server URL will be created from
     * `scramble.api_path` and `scramble.api_domain` config variables. When providing an array, you
     * will need to specify the local server URL manually (if needed).
     *
     * Example of non-default config (final URLs are generated using Laravel `url` helper):
     *
     * ```php
     * 'servers' => [
     *     'Live' => 'api',
     *     'Prod' => 'https://scramble.dedoc.co/api',
     * ],
     * ```
     */
    'servers' => null,

    /**
     * Determines how Scramble stores the descriptions of enum cases.
     * Available options:
     * - 'description' – Case descriptions are stored as the enum schema's description using table formatting.
     * - 'extension' – Case descriptions are stored in the `x-enumDescriptions` enum schema extension.
     *
     *    @see https://redocly.com/docs-legacy/api-reference-docs/specification-extensions/x-enum-descriptions
     * - false - Case descriptions are ignored.
     */
    'enum_cases_description_strategy' => 'description',

    'middleware' => [
        'web',
        RestrictedDocsAccess::class,
    ],

    'extensions' => [],
];
