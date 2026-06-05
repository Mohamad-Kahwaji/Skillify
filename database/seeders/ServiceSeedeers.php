<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeedeers extends Seeder
{
    public function run(): void
    {
        // cities (English names)
        $cities = [
            'Damascus', 'Aleppo',   'Homs',       'Hama',       'Latakia',
            'Tartus',   'Daraa',    'As-Suwayda', 'Idlib',      'Qamishli',
        ];

        /*
         * Each service entry maps to a category / subcategory that already
         * exists after running CategorySeedeers and SubcategorySeedeers.
         * city_idx references the $cities array above.
         * img_seed is used to generate a consistent Picsum image URL.
         */
        $services = [

            // ── Health & Medicine ────────────────────────────────────────
            [
                'name'        => 'Home Visit — General Practitioner',
                'category'    => 'Health & Medicine',
                'subcategory' => 'General Doctor',
                'description' => 'Certified GP visits your home for examination, diagnosis, and prescription.',
                'price'       => 15.00,  'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'doctor1',
            ],
            [
                'name'        => 'Full Dental Treatment Session',
                'category'    => 'Health & Medicine',
                'subcategory' => 'Dentist',
                'description' => 'Cavity treatment, whitening, crown fitting, and extraction with flexible appointments.',
                'price'       => 25.00,  'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'dentist1',
            ],
            [
                'name'        => 'Online Psychiatry Consultation',
                'category'    => 'Health & Medicine',
                'subcategory' => 'Psychiatrist',
                'description' => 'Video-based therapy and counselling sessions with a certified psychiatrist.',
                'price'       => 20.00,  'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'therapy1',
            ],
            [
                'name'        => 'Ophthalmology Check-Up',
                'category'    => 'Health & Medicine',
                'subcategory' => 'Ophthalmologist',
                'description' => 'Complete eye examination including vision test, pressure check, and prescription.',
                'price'       => 18.00,  'price_type' => 'usd',
                'city_idx'    => 2,
                'img_seed'    => 'eye1',
            ],

            // ── Law & Legal ──────────────────────────────────────────────
            [
                'name'        => 'Specialised Legal Consultation',
                'category'    => 'Law & Legal',
                'subcategory' => 'Legal Advisor',
                'description' => 'Comprehensive legal advice on family, commercial, and real-estate matters.',
                'price'       => 2500000, 'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed'    => 'lawyer1',
            ],
            [
                'name'        => 'Sale & Purchase Contract Drafting',
                'category'    => 'Law & Legal',
                'subcategory' => 'Notary',
                'description' => 'Professional drafting and notarisation of real-estate and goods contracts.',
                'price'       => 3000000, 'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'notary1',
            ],

            // ── Engineering ──────────────────────────────────────────────
            [
                'name'        => 'Residential House — Full Architectural Design',
                'category'    => 'Engineering',
                'subcategory' => 'Architect',
                'description' => 'Complete architectural and structural design with execution drawings and BOQ.',
                'price'       => 150.00, 'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'architect1',
            ],
            [
                'name'        => 'On-Site Civil Engineering Supervision',
                'category'    => 'Engineering',
                'subcategory' => 'Civil Engineer',
                'description' => 'Daily and weekly supervision of construction projects to ensure quality standards.',
                'price'       => 80.00,  'price_type' => 'usd',
                'city_idx'    => 2,
                'img_seed'    => 'civil1',
            ],
            [
                'name'        => 'Mobile Application Development',
                'category'    => 'Engineering',
                'subcategory' => 'Software Engineer',
                'description' => 'Professional Android & iOS app development with modern UI/UX design.',
                'price'       => 500.00, 'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'appdev1',
            ],

            // ── Education ────────────────────────────────────────────────
            [
                'name'        => 'High-School Mathematics & Physics Tutoring',
                'category'    => 'Education',
                'subcategory' => 'Teacher',
                'description' => 'Private lessons in maths and physics for secondary students by an experienced teacher.',
                'price'       => 500000, 'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed'    => 'teacher1',
            ],
            [
                'name'        => 'Advanced English Language Course',
                'category'    => 'Education',
                'subcategory' => 'Trainer',
                'description' => 'Intensive English course covering speaking, writing, and IELTS / TOEFL preparation.',
                'price'       => 10.00,  'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'english1',
            ],

            // ── Accounting & Finance ─────────────────────────────────────
            [
                'name'        => 'Monthly Bookkeeping Service',
                'category'    => 'Accounting & Finance',
                'subcategory' => 'Accountant',
                'description' => 'Full bookkeeping, journal entries, and monthly financial statements for businesses.',
                'price'       => 50.00,  'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'accountant1',
            ],
            [
                'name'        => 'Annual Financial Audit',
                'category'    => 'Accounting & Finance',
                'subcategory' => 'Auditor',
                'description' => 'Year-end audit and preparation of closing financial reports for companies.',
                'price'       => 200.00, 'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'audit1',
            ],

            // ── Information Technology ───────────────────────────────────
            [
                'name'        => 'Professional Website Development',
                'category'    => 'Information Technology',
                'subcategory' => 'Web Developer',
                'description' => 'Design and development of professional websites and e-commerce stores with latest tech.',
                'price'       => 300.00, 'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'website1',
            ],
            [
                'name'        => 'Complete Brand Identity Design',
                'category'    => 'Information Technology',
                'subcategory' => 'Graphic Designer',
                'description' => 'Logo, business card, letterhead, and company profile design package.',
                'price'       => 100.00, 'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'design1',
            ],

            // ── Construction ─────────────────────────────────────────────
            [
                'name'        => 'Masonry & Foundation Work',
                'category'    => 'Construction',
                'subcategory' => 'Mason',
                'description' => 'Construction from foundation to walls and roofing with guaranteed quality.',
                'price'       => 4000000, 'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed'    => 'mason1',
            ],
            [
                'name'        => 'Full Apartment Interior Painting',
                'category'    => 'Construction',
                'subcategory' => 'Painter',
                'description' => 'Wall and ceiling painting with premium paint, including surface prep and old-coat removal.',
                'price'       => 1500000, 'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed'    => 'painter1',
            ],
            [
                'name'        => 'Bathroom Tiling & Ceramics',
                'category'    => 'Construction',
                'subcategory' => 'Tiler',
                'description' => 'Precision installation of ceramic and porcelain tiles for floors and walls.',
                'price'       => 2000000, 'price_type' => 'syp',
                'city_idx'    => 4,
                'img_seed'    => 'tiler1',
            ],

            // ── Electrical & Plumbing ────────────────────────────────────
            [
                'name'        => 'Full Home Electrical Wiring',
                'category'    => 'Electrical & Plumbing',
                'subcategory' => 'Electrician',
                'description' => 'Residential electrical network installation including distribution panels and cables.',
                'price'       => 3000000, 'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'electric2',
            ],
            [
                'name'        => 'Central Heating System Installation',
                'category'    => 'Electrical & Plumbing',
                'subcategory' => 'Heating Technician',
                'description' => 'Installing oil, gas, or electric central heating systems with full radiator network.',
                'price'       => 5000000, 'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed'    => 'heating1',
            ],
            [
                'name'        => 'Split AC Installation & Servicing',
                'category'    => 'Electrical & Plumbing',
                'subcategory' => 'AC Technician',
                'description' => 'Installation, maintenance, and inspection of split air conditioners with refrigerant top-up.',
                'price'       => 800000,  'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed'    => 'ac1',
            ],

            // ── Carpentry & Furniture ────────────────────────────────────
            [
                'name'        => 'Custom-Built Wooden Kitchen',
                'category'    => 'Carpentry & Furniture',
                'subcategory' => 'Furniture Maker',
                'description' => 'Design and manufacture of modern fitted kitchens using premium hardwood.',
                'price'       => 12000000, 'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'kitchen1',
            ],
            [
                'name'        => 'Antique Furniture Restoration',
                'category'    => 'Carpentry & Furniture',
                'subcategory' => 'Carpenter',
                'description' => 'Full restoration and rehabilitation of old and antique furniture with part replacement.',
                'price'       => 1000000, 'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed'    => 'furniture1',
            ],

            // ── Tailoring & Textiles ─────────────────────────────────────
            [
                'name'        => 'Custom Wedding Dress Tailoring',
                'category'    => 'Tailoring & Textiles',
                'subcategory' => "Women's Tailor",
                'description' => 'Bespoke bridal and evening gowns made from the finest fabrics and latest designs.',
                'price'       => 100.00,  'price_type' => 'usd',
                'city_idx'    => 4,
                'img_seed'    => 'dress1',
            ],
            [
                'name'        => 'Tailored Men\'s Formal Suit',
                'category'    => 'Tailoring & Textiles',
                'subcategory' => "Men's Tailor",
                'description' => 'Made-to-measure formal and casual suits in premium cashmere and wool fabrics.',
                'price'       => 60.00,   'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'suit1',
            ],

            // ── Cooking & Pastry ─────────────────────────────────────────
            [
                'name'        => 'Wedding & Event Catering Service',
                'category'    => 'Cooking & Pastry',
                'subcategory' => 'Chef',
                'description' => 'Preparation and serving of authentic Syrian cuisine for weddings and large gatherings.',
                'price'       => 35.00,   'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'catering1',
            ],
            [
                'name'        => 'Syrian Oriental Sweets — Custom Orders',
                'category'    => 'Cooking & Pastry',
                'subcategory' => 'Pastry Chef',
                'description' => 'Ma\'amoul, baklava, knafeh, and assorted oriental pastries made to order.',
                'price'       => 500000,  'price_type' => 'syp',
                'city_idx'    => 3,
                'img_seed'    => 'sweets1',
            ],

            // ── Beauty & Care ────────────────────────────────────────────
            [
                'name'        => 'Bridal & Evening Makeup',
                'category'    => 'Beauty & Care',
                'subcategory' => 'Beautician',
                'description' => 'Professional bridal and event makeup with luxury hairstyling.',
                'price'       => 40.00,   'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'makeup1',
            ],
            [
                'name'        => 'Men\'s Haircut & Beard Styling',
                'category'    => 'Beauty & Care',
                'subcategory' => 'Barber',
                'description' => 'Modern haircut and precision beard shaping with hair-care treatments.',
                'price'       => 200000,  'price_type' => 'syp',
                'city_idx'    => 5,
                'img_seed'    => 'barber2',
            ],

            // ── Medical Laboratory ───────────────────────────────────────
            [
                'name'        => 'Complete Blood Count (CBC)',
                'category'    => 'Medical Laboratory',
                'subcategory' => 'Blood Analysis',
                'description' => 'Full CBC blood test with a detailed medical report delivered same day.',
                'price'       => 300000,  'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'bloodtest1',
            ],
            [
                'name'        => 'Hormonal & Thyroid Panel',
                'category'    => 'Medical Laboratory',
                'subcategory' => 'Hormonal Tests',
                'description' => 'Thyroid hormone tests (T3, T4, TSH) along with sex-hormone panel.',
                'price'       => 500000,  'price_type' => 'syp',
                'city_idx'    => 2,
                'img_seed'    => 'hormone1',
            ],

            // ── Photography Studio ───────────────────────────────────────
            [
                'name'        => 'Full Wedding Photography & Videography',
                'category'    => 'Photography Studio',
                'subcategory' => 'Photography',
                'description' => 'Professional photo and video coverage of wedding ceremonies with editing and delivery.',
                'price'       => 200.00,  'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'wedding1',
            ],
            [
                'name'        => 'Commercial Product Photography',
                'category'    => 'Photography Studio',
                'subcategory' => 'Photography',
                'description' => 'Product shots on white and coloured backgrounds for online stores and catalogues.',
                'price'       => 50.00,   'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'product1',
            ],

            // ── Print Workshop ───────────────────────────────────────────
            [
                'name'        => 'Large-Format Banner & Signage Printing',
                'category'    => 'Print Workshop',
                'subcategory' => 'Digital Printing',
                'description' => 'High-quality digital printing of banners, signs, and advertising displays.',
                'price'       => 600000,  'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'banner1',
            ],
            [
                'name'        => 'Catalogue & Brochure Offset Printing',
                'category'    => 'Print Workshop',
                'subcategory' => 'Offset Printing',
                'description' => 'Design and offset printing of catalogues and promotional brochures at high resolution.',
                'price'       => 1000000, 'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed'    => 'brochure1',
            ],

            // ── Tech Company ─────────────────────────────────────────────
            [
                'name'        => 'Enterprise ERP System Development',
                'category'    => 'Tech Company',
                'subcategory' => 'Software Development',
                'description' => 'End-to-end ERP solution for inventory, accounting, HR, and operations management.',
                'price'       => 2000.00, 'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'erp1',
            ],
            [
                'name'        => 'Cloud Hosting & Data Backup Service',
                'category'    => 'Tech Company',
                'subcategory' => 'Cloud Services',
                'description' => 'Secure cloud hosting with automated backup solutions and 24/7 technical support.',
                'price'       => 30.00,   'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'cloud1',
            ],

            // ── Contracting Company ──────────────────────────────────────
            [
                'name'        => 'Turnkey Residential Villa Construction',
                'category'    => 'Contracting Company',
                'subcategory' => 'General Contracting',
                'description' => 'Full construction from ground-up to complete finishing with premium-grade materials.',
                'price'       => 50000.00,'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'villa1',
            ],
            [
                'name'        => 'Luxury Interior Design — 200 sqm Apartment',
                'category'    => 'Contracting Company',
                'subcategory' => 'Decoration Contracting',
                'description' => 'High-end interior decoration execution for residential apartments and offices.',
                'price'       => 8000.00, 'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'decor1',
            ],

            // ── Shipping & Transport ─────────────────────────────────────
            [
                'name'        => 'Inter-City Furniture Relocation',
                'category'    => 'Shipping & Transport',
                'subcategory' => 'Furniture Moving',
                'description' => 'Professional furniture and household goods relocation between Syrian cities with full insurance.',
                'price'       => 1500000, 'price_type' => 'syp',
                'city_idx'    => 5,
                'img_seed'    => 'moving1',
            ],
            [
                'name'        => 'Cross-Border Land Freight',
                'category'    => 'Shipping & Transport',
                'subcategory' => 'Land Shipping',
                'description' => 'Goods shipment to Lebanon, Jordan, Turkey, and neighbouring countries by road.',
                'price'       => 2.50,    'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'shipping2',
            ],

            // ── Real Estate Company ──────────────────────────────────────
            [
                'name'        => 'Residential Apartments for Sale — Mazzeh District',
                'category'    => 'Real Estate Company',
                'subcategory' => 'Real Estate Sales',
                'description' => 'Various-sized apartments for sale in Mazzeh and Kafarsouseh at competitive prices.',
                'price'       => 80000.00,'price_type' => 'usd',
                'city_idx'    => 0,
                'img_seed'    => 'apartment1',
            ],
            [
                'name'        => 'Commercial Office Space for Rent',
                'category'    => 'Real Estate Company',
                'subcategory' => 'Real Estate Rental',
                'description' => 'Office and retail space rental in prime business districts of Aleppo and Damascus.',
                'price'       => 500.00,  'price_type' => 'usd',
                'city_idx'    => 1,
                'img_seed'    => 'office1',
            ],

            // ── Services Company ─────────────────────────────────────────
            [
                'name'        => 'Residential Cleaning Service',
                'category'    => 'Services Company',
                'subcategory' => 'Cleaning & Maintenance',
                'description' => 'Comprehensive home and apartment cleaning with specialised products and trained staff.',
                'price'       => 400000,  'price_type' => 'syp',
                'city_idx'    => 0,
                'img_seed'    => 'cleaning1',
            ],
            [
                'name'        => 'Security & Guard Services for Facilities',
                'category'    => 'Services Company',
                'subcategory' => 'Security & Guard',
                'description' => 'Trained security personnel for commercial establishments and residential compounds.',
                'price'       => 600000,  'price_type' => 'syp',
                'city_idx'    => 1,
                'img_seed'    => 'security1',
            ],
        ];

        foreach ($services as $srv) {
            $city = $cities[$srv['city_idx'] % \count($cities)];

            Service::firstOrCreate(
                ['name' => $srv['name'], 'city' => $city],
                [
                    'description' => $srv['description'],
                    'category'    => $srv['category'],
                    'subcategory' => $srv['subcategory'],
                    'city'        => $city,
                    'image'       => "https://picsum.photos/seed/{$srv['img_seed']}/640/480",
                    'price'       => $srv['price'],
                    'price_type'  => $srv['price_type'],
                ]
            );
        }
    }
}
