<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Admin's Events (creator_id: 1)
        $adminEvents = [
            [
                'title' => 'Tech Conference 2025',
                'description' => 'Annual technology conference featuring the latest innovations in AI, ML, and Cloud Computing',
                'date' => Carbon::create(2025, 6, 15, 9, 0, 0),
                'location' => 'Silicon Valley Convention Center',
                'max_tickets' => 1000,
                'available_tickets' => 1000,
                'price' => 299.99,
                'creator_id' => 1, // Admin
                'status' => 'published',
                'speakers' => 'John Smith (AI Expert), Maria Garcia (Cloud Architect), David Chen (ML Engineer)',
                'sponsors' => 'TechCorp, InnovateNow, CloudMasters',
                'image' => 'https://raw.githubusercontent.com/chabbasaad/Events_Microservices/master/images_events/event1.jpg'
            ],
            [
                'title' => 'Startup Summit',
                'description' => 'Connect with successful entrepreneurs, investors, and industry leaders',
                'date' => Carbon::create(2025, 7, 20, 10, 0, 0),
                'location' => 'Business Innovation Hub',
                'max_tickets' => 500,
                'available_tickets' => 500,
                'price' => 199.99,
                'creator_id' => 1, // Admin
                'status' => 'published',
                'speakers' => 'Sarah Johnson (VC), Michael Chang (Serial Entrepreneur), Lisa Brown (Angel Investor)',
                'sponsors' => 'VentureCapital Inc, StartupBoost, InvestorNetwork',
                'image' => 'https://raw.githubusercontent.com/chabbasaad/Events_Microservices/master/images_events/event2.jpg'
            ],
            [
                'title' => 'Digital Marketing Masterclass',
                'description' => 'Learn advanced digital marketing strategies from industry experts',
                'date' => Carbon::create(2025, 8, 10, 13, 0, 0),
                'location' => 'Digital Marketing Institute',
                'max_tickets' => 200,
                'available_tickets' => 200,
                'price' => 149.99,
                'creator_id' => 1, // Admin
                'status' => 'published',
                'speakers' => 'Emma Wilson (SEO Expert), Tom Davis (Social Media Strategist)',
                'sponsors' => 'DigitalPro, MarketingMasters, SocialBoost',
                'image' => 'https://raw.githubusercontent.com/chabbasaad/Events_Microservices/master/images_events/event3.jpg'
            ],
        ];

        // Event Creator's Events (creator_id: 3)
        $eventCreatorEvents = [
            [
                'title' => 'Web3 Development Workshop',
                'description' => 'Hands-on workshop on blockchain, smart contracts, and decentralized applications',
                'date' => Carbon::create(2025, 9, 5, 14, 0, 0),
                'location' => 'Blockchain Innovation Center',
                'max_tickets' => 100,
                'available_tickets' => 100,
                'price' => 399.99,
                'creator_id' => 3, // Event Creator
                'status' => 'published',
                'speakers' => 'Alex Thompson (Blockchain Expert), Nina Patel (Smart Contract Developer)',
                'sponsors' => 'Web3Future, BlockchainTech, CryptoInnovate',
                'image' => 'https://raw.githubusercontent.com/chabbasaad/Events_Microservices/master/images_events/event4.jpg'
            ],
            [
                'title' => 'Data Science Bootcamp',
                'description' => 'Intensive 3-day bootcamp covering data analysis, visualization, and machine learning',
                'date' => Carbon::create(2025, 10, 15, 9, 0, 0),
                'location' => 'Data Science Academy',
                'max_tickets' => 150,
                'available_tickets' => 150,
                'price' => 599.99,
                'creator_id' => 3, // Event Creator
                'status' => 'published',
                'speakers' => 'Dr. Robert Lee (Data Scientist), Emily Chen (ML Researcher)',
                'sponsors' => 'DataCorp, AnalyticsPro, MLMasters',
                'image' => 'https://raw.githubusercontent.com/chabbasaad/Events_Microservices/master/images_events/event5.jpg'
            ],
            [
                'title' => 'DevOps Summit 2025',
                'description' => 'Learn about the latest DevOps practices, tools, and methodologies',
                'date' => Carbon::create(2025, 11, 20, 10, 0, 0),
                'location' => 'DevOps Conference Center',
                'max_tickets' => 300,
                'available_tickets' => 300,
                'price' => 249.99,
                'creator_id' => 3, // Event Creator
                'status' => 'published',
                'speakers' => 'James Wilson (DevOps Engineer), Anna Martinez (Cloud Architect)',
                'sponsors' => 'DevOpsPro, CloudTech, InfrastructureNow',
                'image' => 'https://raw.githubusercontent.com/chabbasaad/Events_Microservices/master/images_events/event6.jpg'
            ],
        ];

        // Insert all events
        foreach ($adminEvents as $event) {
            Event::create($event);
        }

        foreach ($eventCreatorEvents as $event) {
            Event::create($event);
        }
    }
}
