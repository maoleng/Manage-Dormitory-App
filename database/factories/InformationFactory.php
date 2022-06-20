<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Information>
 */
class InformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string,mixed>
     */
    public function definition()
    {
        $ethnics = [
            'Kinh', 'Tày', 'Thái', 'Hoa', 'Khơ me', 'Mường', 'Nùng', 'HMông', 'Dao', 'Gia rai', 'Ngái', 'Ê đê', 'Ba na', 'Xơ Đăng', 'Sán Chay', 'Cơ ho', 'Chăm', 'Sán Dìu', 'Hrê', 'Mnông', 'Ra glai', 'Xtiêng', 'Bru Vân Kiều', 'Thổ', 'Giáy', 'Cơ tu', 'Gié Triêng', 'Mạ', 'Khơ mú', 'Co', 'Tà ôi', 'Chơ ro', 'Kháng', 'Xinh mun', 'Hà Nhì', 'Chu ru', 'Lào', 'La Chí', 'La Ha', 'Phù Lá', 'La Hủ', 'Lự', 'Lô Lô', 'Chứt', 'Mảng', 'Pà Thẻn', 'Co Lao', 'Cống', 'Bố Y', 'Si La', 'Pu Péo', 'Brâu', 'Ơ Đu', 'Rơ'
        ];
        return [
            'birthday' => $this->faker->dateTime,
            'gender' => $this->faker->numberBetween(0,1),
            'birthplace' => $this->faker->address,
            'ethnic' => $this->faker->randomElement($ethnics),
            'religion' => $this->faker->randomElement(['Không', 'Phật giáo']),
            'phone' => $this->faker->phoneNumber,
            'identify_card' => $this->faker->creditCardNumber,
            'address' => $this->faker->address,
            'area' => $this->faker->randomElement(['KV1', 'KV2', 'KV3']),
        ];
    }
}

