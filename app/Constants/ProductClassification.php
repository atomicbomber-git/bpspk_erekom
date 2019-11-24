<?php

namespace App\Constants;

class ProductClassification
{
    public static function get()
    {
        return [
            "Utuh" => [
                "code" => 1,

                "items" => [
                    "Mati" => [
                        "code" => 1,

                        "items" => [
                            "Segar" => "A",
                            "Beku" => "B",
                            "Telur" => "C",
                        ],
                    ],

                    "Hidup" => [
                        "code" => 2,

                        "items" => [
                            "Ikan" => "A",
                            "Telur" => "B",
                        ]
                    ],
                ],
            ],  
            "Bagian Tubuh" => [
                "code" => 2,
                "items" => [
                    "Beku" => [
                        "code" => 1,
                        "items" => [
                            "Moncong" => "A",
                            "Kepala" => "B",
                            "Sirip" => "C",
                            "Sirip Anal" => "D",
                            "Ekor" => "E",
                            "Ekor Bagian Atas" => "F",
                            "Kulit" => "G",
                            "Gigi" => "H",
                            "Tulang" => "I",
                            "Badan" => "J",
                            "Daging" => "K",
                            "Insang" => "L",
                        ]
                    ],
                    "Segar" => [
                        "code" => 2,
                        "items" => [
                            "Moncong" => "A",
                            "Kepala" => "B",
                            "Sirip" => "C",
                            "Sirip Anal" => "D",
                            "Ekor" => "E",
                            "Ekor Bagian Atas" => "F",
                            "Kulit" => "G",
                            "Gigi" => "H",
                            "Tulang" => "I",
                            "Badan" => "J",
                            "Daging" => "K",
                            "Insang" => "L",
                        ]
                    ],
                    "Kering" => [
                        "code" => 3,
                        "items" => [
                            "Moncong" => "A",
                            "Kepala" => "B",
                            "Sirip" => "C",
                            "Sirip Anal" => "D",
                            "Ekor" => "E",
                            "Ekor Bagian Atas" => "F",
                            "Kulit" => "G",
                            "Gigi" => "H",
                            "Tulang" => "I",
                            "Badan" => "J",
                            "Daging" => "K",
                            "Insang" => "L",
                        ]
                    ],
                ]
            ],  
            "Derivat" => [
                "code" => 3,
                "items" => [
                    "Segar" => [
                        "code" => 1,
                        "items" => [
                            "Cartilase" => "A",
                            "Minyak" => "B",
                            "Cacahan Kulit" => "C",
                            "Cacahan Daging" => "D",
                            "Kulit Samak" => "E",
                            "Sirip Kupas" => "F",
                            "Hisit" => "G",
                        ]
                    ],
                    "Kering" => [
                        "code" => 2,
                        "items" => [
                            "Cartilase" => "A",
                            "Cacahan Kulit" => "B",
                            "Cacahan Daging" => "C",
                            "Kulit Samak" => "D",
                            "Sirip Kupas" => "E",
                            "Hisit" => "F",
                        ]
                    ],
                    "Beku" => [
                        "code" => 3,
                        "items" => [
                            "Cartilase" => "A",
                            "Cacahan Kulit" => "B",
                            "Cacahan Daging" => "C",
                            "Kulit Samak" => "D",
                            "Sirip Kupas" => "E",
                            "Hisit" => "F",
                        ]
                    ],
                ]
            ],  
        ];
    }
}