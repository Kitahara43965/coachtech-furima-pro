<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\PurchaseMethod;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $condition1 = Condition::create([
            'name' => '良好'
        ]);
        $condition2 = Condition::create([
            'name' => '目立った傷や汚れなし'
        ]);
        $condition3 = Condition::create([
            'name' => 'やや傷や汚れあり'
        ]);
        $condition4 = Condition::create([
            'name' => '状態が悪い'
        ]);


        $purchaseMethod1 = PurchaseMethod::create([
            'name' => 'コンビニ払い'
        ]);
        $purchaseMethod2 = PurchaseMethod::create([
            'name' => 'カード支払い'
        ]);

        $category1 = Category::create([
            'name' => 'ファッション'
        ]);
        $category2 = Category::create([
            'name' => '家電'
        ]);
        $category3 = Category::create([
            'name' => 'インテリア'
        ]);
        $category4 = Category::create([
            'name' => 'レディース'
        ]);
        $category5 = Category::create([
            'name' => 'メンズ'
        ]);
        $category6 = Category::create([
            'name' => 'コスメ'
        ]);
        $category7 = Category::create([
            'name' => '本'
        ]);
        $category8 = Category::create([
            'name' => 'ゲーム'
        ]);
        $category9 = Category::create([
            'name' => 'スポーツ'
        ]);
        $category10 = Category::create([
            'name' => 'キッチン'
        ]);
        $category11 = Category::create([
            'name' => 'ハンドメイド'
        ]);
        $category12 = Category::create([
            'name' => 'アクセサリー'
        ]);
        $category13 = Category::create([
            'name' => 'おもちゃ'
        ]);
        $category14 = Category::create([
            'name' => 'ベビー・キッズ'
        ]);

        $item1 = Item::create([
            'is_default' => true,
            'condition_id' => $condition1->id,
            'name' => '腕時計',
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'image' => 'Armani+Mens+Clock.jpg',
        ]);

        $item2 = Item::create([
            'is_default' => true,
            'condition_id' => $condition2->id,
            'name' => 'HDD',
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => 5000,
            'image' => 'HDD+Hard+Disk.jpg',
        ]);

        $item3 = Item::create([
            'is_default' => true,
            'condition_id' => $condition3->id,
            'name' => '玉ねぎ3束',
            'brand' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => 300,
            'image' => 'iLoveIMG+d.jpg',
        ]);

        $item4 = Item::create([
            'is_default' => true,
            'condition_id' => $condition4->id,
            'name' => '革靴',
            'brand' => '',
            'description' => 'クラシックなデザインの革靴',
            'price' => 4000,
            'image' => 'Leather+Shoes+Product+Photo.jpg',
        ]);

        $item5 = Item::create([
            'is_default' => true,
            'condition_id' => $condition1->id,
            'name' => 'ノートPC',
            'brand' => '',
            'description' => '高性能なノートパソコン',
            'price' => 45000,
            'image' => 'Living+Room+Laptop.jpg',
        ]);

        $item6 = Item::create([
            'is_default' => true,
            'condition_id' => $condition2->id,
            'name' => 'マイク',
            'brand' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'price' => 8000,
            'image' => 'Music+Mic+4632231.jpg',
        ]);

        $item7 = Item::create([
            'is_default' => true,
            'condition_id' => $condition3->id,
            'name' => 'ショルダーバッグ',
            'brand' => '',
            'description' => 'おしゃれなショルダーバッグ',
            'price' => 3500,
            'image' => 'Purse+fashion+pocket.jpg',
        ]);

        $item8 = Item::create([
            'is_default' => true,
            'condition_id' => $condition4->id,
            'name' => 'タンブラー',
            'brand' => 'なし',
            'description' => '使いやすいタンブラー',
            'price' => 500,
            'image' => 'Tumbler+souvenir.jpg',
        ]);

        $item9 = Item::create([
            'is_default' => true,
            'condition_id' => $condition1->id,
            'name' => 'コーヒーミル',
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'price' => 4000,
            'image' => 'Waitress+with+Coffee+Grinder.jpg',
        ]);

        $item10 = Item::create([
            'is_default' => true,
            'condition_id' => $condition2->id,
            'name' => 'メイクセット',
            'brand' => '',
            'description' => '便利なメイクアップセット',
            'price' => 2500,
            'image' => '%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
        ]);


        $user1 = User::create([
            'name' => 'user1',
            'email' => 'user1@mail.com',
            'password' => Hash::make('user1test'),
            'is_filled_with_profile' => true,
            'username' => '山田 一郎',
            'postcode' => '123-4567',
            'address' => '北海道札幌市AAAA',
            'building' => 'Aハイツ',
            'image' => null,
        ]);

        $user2 = User::create([
            'name' => 'user2',
            'email' => 'user2@mail.com',
            'password' => Hash::make('user2test'),
            'is_filled_with_profile' => true,
            'username' => '山田 仁子',
            'postcode' => '123-4567',
            'address' => '北海道札幌市AAAA',
            'building' => 'Aハイツ',
            'image' => null,
        ]);

        $user3 = User::create([
            'name' => 'user3',
            'email' => 'user3@mail.com',
            'password' => Hash::make('user3test'),
            'is_filled_with_profile' => true,
            'username' => '山田 三郎',
            'postcode' => '123-4567',
            'address' => '北海道札幌市AAAA',
            'building' => 'Aハイツ',
            'image' => null,
        ]);

        $item1->categories()->syncWithoutDetaching([$category5->id]);
        $item2->categories()->syncWithoutDetaching([$category2->id,$category3->id]);
        $item3->categories()->syncWithoutDetaching([$category10->id]);
        $item4->categories()->syncWithoutDetaching([$category1->id,$category12->id]);
        $item5->categories()->syncWithoutDetaching([$category2->id]);
        $item6->categories()->syncWithoutDetaching([$category2->id,$category3->id]);
        $item7->categories()->syncWithoutDetaching([$category1->id,$category4->id]);
        $item8->categories()->syncWithoutDetaching([$category10->id]);
        $item9->categories()->syncWithoutDetaching([$category3->id,$category10->id]);
        $item10->categories()->syncWithoutDetaching([$category1->id,$category12->id]);

        $user1->items()->syncWithoutDetaching([
            $item1->id => ['type' => 'ownership'],
            $item2->id => ['type' => 'ownership'],
            $item3->id => ['type' => 'ownership'],
            $item4->id => ['type' => 'ownership'],
            $item5->id => ['type' => 'ownership'],
        ]);

        $user2->items()->syncWithoutDetaching([
            $item6->id => ['type' => 'ownership'],
            $item7->id => ['type' => 'ownership'],
            $item8->id => ['type' => 'ownership'],
            $item9->id => ['type' => 'ownership'],
            $item10->id => ['type' => 'ownership'],
        ]);
        
    }
}
