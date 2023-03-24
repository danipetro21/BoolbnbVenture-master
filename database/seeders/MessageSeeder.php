<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\Property;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
 
    $emails = [
        'adam.joshua@gmail.com',
        'adam.smith@gmail.com',
        'natalie.williams@yahoo.com',
        'chad.harris@hotmail.com',
        'lauren.campbell@gmail.com',
        'timothy.jones@yahoo.com',
        'katherine.miller@hotmail.com',
        'isaac.nguyen@gmail.com',
        'jennifer.wilson@yahoo.com',
        'lucas.anderson@hotmail.com',
        'madison.parker@gmail.com',
        'samuel.wilson@gmail.com',
        'emily.martin@yahoo.com',
        'robert.miller@hotmail.com',
        'ashley.nguyen@gmail.com',
        'kyle.jones@yahoo.com',
        'sophia.smith@hotmail.com',
        'justin.jackson@gmail.com',
        'grace.brown@yahoo.com',
        'nathan.lee@hotmail.com',
        'hannah.anderson@gmail.com',
        'peter.nguyen@yahoo.com',
        'olivia.johnson@hotmail.com',
        'eric.thomas@gmail.com',
        'alice.taylor@yahoo.com',
        'steven.wilson@hotmail.com',
        'sara.thompson@gmail.com',
        'jared.mitchell@yahoo.com',
        'susan.baker@hotmail.com',
        'derek.evans@gmail.com',
        'rebecca.green@yahoo.com',
        'carla.martinez@gmail.com',
        'edward.smith@yahoo.com',
        'maria.sanchez@hotmail.com',
        'chris.turner@gmail.com',
        'laura.rodriguez@yahoo.com',
        'david.baker@hotmail.com',
        'ana.hernandez@gmail.com',
        'patrick.white@yahoo.com',
        'jessica.collins@hotmail.com',
        'mark.johnson@gmail.com',
        'emma.wilson@gmail.com',
        'oliver.harris@yahoo.com',
        'amanda.lee@hotmail.com',
        'jacob.nguyen@gmail.com',
        'julia.wright@yahoo.com',
        'daniel.evans@hotmail.com',
        'lily.thompson@gmail.com',
        'ryan.ross@yahoo.com',
        'olivia.taylor@hotmail.com',
        'matthew.phillips@gmail.com'
    ];
    
    $messages = [
        'Hi, I am interested in your property and would like to know more about the nearby hospitals or medical facilities. Can you recommend any good clinics or doctors in the area?',
        'Hello, I came across your property and was wondering if there are any security cameras or other security measures in place?',
        'Hi there, I am looking for a place to stay for a long-term lease and your property looks like a great option. Can you let me know if there are any discounts available for extended stays?',
        'Hello, I am interested in your property and would like to know more about the nearby schools or educational facilities. Can you recommend any good schools in the area?',
        'Hi, I am interested in renting your property for a weekend retreat and would like to know if there are any outdoor recreation activities available nearby?',
        'Hello, I was impressed by your property and would like to know more about the history and culture of the area. Can you recommend any good historical landmarks or museums in the area?',
        'Hi, I am interested in your property and would like to know more about the nearby public transportation options. Are there any bus stops or subway stations nearby?',
        'Hello, I came across your property and was wondering if there are any additional fees or charges I should be aware of?',
        'Hi there, I am looking for a place to stay for a group trip and your property looks perfect. Can you let me know if there are any common areas or shared spaces?',
        'Hello, I am interested in your property and would like to know more about the cleaning and maintenance services available. Are there any cleaning staff or maintenance personnel on site?',
        'Hi, I am interested in renting your property for a business trip and would like to know if there is a high-speed internet connection available?',
        'Hello, I was impressed by your property and would like to know more about the nearby cultural and artistic attractions. Can you recommend any museums or galleries in the area?',
        'Hi there, I am interested in renting your property and would like to know more about the nearby beaches and waterfront areas. Are there any good places to swim or sunbathe?',
        'Hello, I am interested in your property and would like to know more about the nearby hiking and outdoor recreation areas. Can you recommend any good trails or parks in the area?',
        'Hi, I saw your property listing and was wondering if there is a security system or lockbox available for key exchange?',
        'Hello, I am interested in your property and would like to know more about the nearby universities or colleges. Can you recommend any good schools in the area?',
        'Hi, I am interested in your property and would like to know more about the kitchen facilities. Are there any cooking utensils or appliances available?',
        'Hello, I came across your property and was wondering if there are any fitness or exercise facilities available in the building?',
        'Hi there, I am looking for a place to stay for a romantic getaway and your property looks perfect. Can you let me know if there is a private balcony or outdoor area?',
        'Hello, I am interested in your property and would like to know more about the nearby grocery stores and markets. Can you recommend any good places to shop for food?',
        'Hi, I am interested in renting your property for a family vacation and would like to know if there are any children\'s activities or play areas available?',
        'Hello, I was impressed by your property and would like to know more about the nearby public parks or green spaces. Can you recommend any good places to go for a walk or picnic?',
        'Hi there, I am interested in renting your property and would like to know more about the nearby shopping and retail options. Are there any malls or outlets in the area?',
        'Hello, I am interested in your property and would like to know more about the nearby nightlife and entertainment options. Can you recommend any good places to go out?',
        'Hi, I saw your property listing and was wondering if there are any restrictions on smoking or vaping?',
        'Hello, I am interested in your property and would like to know more about the nearby medical facilities and hospitals. Can you recommend any good clinics or doctors in the area?',
        'Hi, I am interested in your property and would like to know more about the neighborhood. Is it a safe area?',
        'Hello, I came across your property and was wondering if you offer any additional services such as cleaning or laundry?',
        'Hi there, I am looking for a place to stay for a work trip and your property looks perfect. Can you let me know if there is a workspace or desk available?',
        'Hello, I am interested in your property and would like to know if there are any restrictions on pets or animals?',
        'Hi, I am interested in renting your property for a special event. Can you let me know if there are any restrictions on the number of guests allowed?',
        'Hello, I was impressed by your property and would like to know more about the parking situation. Is there designated parking available?',
        'Hi there, I am interested in renting your property and would like to know more about the nearby restaurants and entertainment options. Can you recommend any good places to eat or visit?',
        'Hello, I am interested in your property and would like to know more about the security measures in place. Are there any security cameras or guards?',
        'Hi, I saw your property listing and was wondering if there are any rules or policies regarding noise levels or quiet hours?',
        'Hello, I am interested in your property and would like to know more about the lease agreement. Can you let me know what the terms and conditions are?',
        'Hi, I am interested in your property and would like to know more about the surrounding area. Are there any good restaurants or cafes nearby?',
        'Hello, I saw your property online and was wondering if you could tell me more about the nearby public transportation options. How far is the nearest bus stop or train station?',
        'Hi there, I am looking for a place to stay for a few months and your property looks like a great option. Would it be possible to schedule a tour of the property sometime this week?',
        'Hello, I am very interested in your property and have a few questions before I make a decision. Can you tell me more about the security measures in place and if there is a security deposit required?',
        'Hi, I am interested in renting your property for a weekend getaway. Can you let me know if there are any restrictions on check-in or check-out times?',
        'Hello, I was impressed by your property listing and would like to schedule a viewing. Can you let me know what dates are available?',
        'Hi there, I am interested in renting your property and would like to know more about the appliances and furnishings that are included. Are there any restrictions on their use?',
        'Hello, I am interested in your property and would like to know more about the amenities available in the building. Are there any common areas or facilities?',
        'Hi, I saw your property listing and was wondering if it would be possible to negotiate the rental price for a longer stay. Are there any discounts available for extended stays?',
        'Hello, I am considering your property for a family vacation and would like to know more about the nearby attractions and activities. Can you recommend any places to visit in the area?',
        'Hi there, I came across your property listing on Boolbnb and I was wondering if you could provide more information about the amenities and services available. Also, would it be possible to schedule a viewing of the property at your convenience? Thank you in advance.',
        'Greetings, I\'m interested in your property for a short-term stay, but I was hoping you could clarify a few things about the location and the surrounding area. Is it within walking distance to any shops or restaurants? And are there any nearby parks or attractions? I appreciate any information you can provide.',
        'Hello, I hope this message finds you well. Your property caught my eye and I was hoping you could give me a little more insight into what the rental process looks like. How long is the lease typically for? Are utilities included? And what is the security deposit? Thanks for your help.',
        'Good day, I\'m very interested in your property and I wanted to inquire about the availability for the dates of my intended stay. Also, are there any house rules or policies that I should be aware of? I look forward to hearing back from you.',
        'Hi, I came across your property listing and I was wondering if you could provide more information about the amenities and appliances that are included. Is there a washer and dryer on site? And what type of kitchen appliances are available? Thanks for your time and assistance.'
    ];

    // Get all property IDs from the properties table
    $propertyIds = DB::table('properties')->pluck('id')->toArray();

    // Loop through each email and message, assigning them to a property
    for ($i = 0; $i < count($emails); $i++) {
    $propertyId = $propertyIds[$i % count($propertyIds)]; // Use modulus to cycle through properties
    DB::table('messages')->insert([
        'property_id' => $propertyId,
        'email' => $emails[$i],
        'message' => $messages[$i],
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

}

}