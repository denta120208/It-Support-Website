<?php
    use \koolreport\excel\Table;
    use \koolreport\excel\PivotTable;
    use \koolreport\excel\BarChart;
    use \koolreport\excel\LineChart;

    $sheet1 = "GOP";
?>
<meta charset="UTF-8">

<div sheet-name="<?php echo $sheet1; ?>">
    <?php
    $styleArray = [
        'font' => [
            'name' => 'Calibri', //'Verdana', 'Arial'
            'size' => 30,
            'bold' => true,
            'italic' => FALSE,
            'underline' => 'none', //'double', 'doubleAccounting', 'single', 'singleAccounting'
            'strikethrough' => FALSE,
            'superscript' => false,
            'subscript' => false,
            'color' => [
                'rgb' => '000000',
                'argb' => 'FF000000',
            ]
        ],
        'alignment' => [
            'horizontal' => 'general',//left, right, center, centerContinuous, justify, fill, distributed
            'vertical' => 'bottom',//top, center, justify, distributed
            'textRotation' => 0,
            'wrapText' => false,
            'shrinkToFit' => false,
            'indent' => 0,
            'readOrder' => 0,
        ],
        'borders' => [
            'top' => [
                'borderStyle' => 'none', //dashDot, dashDotDot, dashed, dotted, double, hair, medium, mediumDashDot, mediumDashDotDot, mediumDashed, slantDashDot, thick, thin
                'color' => [
                    'rgb' => '808080',
                    'argb' => 'FF808080',
                ]
            ],
            //left, right, bottom, diagonal, allBorders, outline, inside, vertical, horizontal
        ],
        'fill' => [
            'fillType' => 'none', //'solid', 'linear', 'path', 'darkDown', 'darkGray', 'darkGrid', 'darkHorizontal', 'darkTrellis', 'darkUp', 'darkVertical', 'gray0625', 'gray125', 'lightDown', 'lightGray', 'lightGrid', 'lightHorizontal', 'lightTrellis', 'lightUp', 'lightVertical', 'mediumGray'
            'rotation' => 90,
            'color' => [
                'rgb' => 'A0A0A0',
                'argb' => 'FFA0A0A0',
            ],
            'startColor' => [
                'rgb' => 'A0A0A0',
                'argb' => 'FFA0A0A0',
            ],
            'endColor' => [
                'argb' => 'FFFFFF',
                'argb' => 'FFFFFFFF',
            ],
        ],
    ];
    ?>
    <div>Finance Accounting</div>

    <div range="A3:A4">
        <?php
        \koolreport\excel\Text::create(array(
            "text" => 'KETERANGAN',
            "excelStyle" => [
                'font' => [
                    'name' => 'Calibri', //'Verdana', 'Arial'
                    'bold' => true,
                    'italic' => FALSE,
                    'color' => [
                        'rgb' => '000000',
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                ],
            ]
        ));
        ?>
    </div>

    <div range="B3:B4">
        <?php
        $dateParam = DateTime::createFromFormat('Ym', $this->cut_off);
        \koolreport\excel\Text::create(array(
            "text" => "ACTUAL YTD " . $dateParam->format('F') . ' ' . ($dateParam->format('Y') - 1),
            "excelStyle" => [
                'font' => [
                    'name' => 'Calibri', //'Verdana', 'Arial'
                    'bold' => true,
                    'italic' => FALSE,
                    'color' => [
                        'rgb' => '000000',
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                ],
            ]
        ));
        ?>
    </div>

    <!-- <div range="C3:E3"> -->
    <div range="C3:D3">
        <?php
        \koolreport\excel\Text::create(array(
            "text" => 'CURRENT MONTH',
            "excelStyle" => [
                'font' => [
                    'name' => 'Calibri', //'Verdana', 'Arial'
                    'bold' => true,
                    'italic' => FALSE,
                    'color' => [
                        'rgb' => '000000',
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                ],
            ]
        ));
        ?>
    </div>

    <!-- <div range="F3:F4"> -->
    <div range="E3:E4">
        <?php
        $dateParam = DateTime::createFromFormat('Ym', $this->cut_off);
        \koolreport\excel\Text::create(array(
            "text" => "ACTUAL YTD " . $dateParam->format('F Y'),
            "excelStyle" => [
                'font' => [
                    'name' => 'Calibri', //'Verdana', 'Arial'
                    'bold' => true,
                    'italic' => FALSE,
                    'color' => [
                        'rgb' => '000000',
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                ],
            ]
        ));
        ?>
    </div>

    <!-- <div range="G3:G4"> -->
    <div range="F3:F4">
        <?php
        $dateParam = DateTime::createFromFormat('Ym', $this->cut_off);
        \koolreport\excel\Text::create(array(
            "text" => "BUDGET YTD " . $dateParam->format('F Y'),
            "excelStyle" => [
                'font' => [
                    'name' => 'Calibri', //'Verdana', 'Arial'
                    'bold' => true,
                    'italic' => FALSE,
                    'color' => [
                        'rgb' => '000000',
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                ],
            ]
        ));
        ?>
    </div>

    <!-- <div range="H3:H4"> -->
    <div range="G3:G4">
        <?php
        $dateParam = DateTime::createFromFormat('Ym', $this->cut_off);
        \koolreport\excel\Text::create(array(
            "text" => "ACHIEVEMENT UP TO",
            "excelStyle" => [
                'font' => [
                    'name' => 'Calibri', //'Verdana', 'Arial'
                    'bold' => true,
                    'italic' => FALSE,
                    'color' => [
                        'rgb' => '000000',
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                ],
            ]
        ));
        ?>
    </div>

    <!-- <div range="I3:I4"> -->
    <div range="H3:H4">
        <?php
        $dateParam = DateTime::createFromFormat('Ym', $this->cut_off);
        \koolreport\excel\Text::create(array(
            "text" => "GROWTH",
            "excelStyle" => [
                'font' => [
                    'name' => 'Calibri', //'Verdana', 'Arial'
                    'bold' => true,
                    'italic' => FALSE,
                    'color' => [
                        'rgb' => '000000',
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                ],
            ]
        ));
        ?>
    </div>

    <!-- <div range="J3:J4"> -->
    <div range="I3:I4">
        <?php
        $dateParam = DateTime::createFromFormat('Ym', $this->cut_off);
        \koolreport\excel\Text::create(array(
            "text" => "BUDGET FY " . $dateParam->format('Y'),
            "excelStyle" => [
                'font' => [
                    'name' => 'Calibri', //'Verdana', 'Arial'
                    'bold' => true,
                    'italic' => FALSE,
                    'color' => [
                        'rgb' => '000000',
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                ],
            ]
        ));
        ?>
    </div>

    <div cell="A4">
        <?php
        $dateParam = DateTime::createFromFormat('Ym', $this->cut_off);
        Table::create(array(
            "dataSource" => $this->dataStore('finance_accounting_gop_excel'),
            // "showFooter"=>"false",
            "columns" => array(
                "GOP_GROUP_DASHBOARD_NAME" => [
                    "label" => "KETERANGAN"
                ],
                "ACTUAL_YTD_BACKWARD" => [
                    "label" => "ACTUAL YTD " . $dateParam->format('F') . ' ' . ($dateParam->format('Y') - 1)
                ],
                "CURRENT_MONTH_RES" => [
                    "label" => "Res"
                ],
                // "CURRENT_MONTH_SPORT_CLUB" => [
                //     "label" => "Sport Facility"
                // ],
                "TOTAL_CURRENT_MONTH" => [
                    "label" => "Total"
                ],
                "ACTUAL_YTD_CURRENT" => [
                    "label" => "ACTUAL YTD " . $dateParam->format('F Y')
                ],
                "BUDGET_YTD_CURRENT" => [
                    "label" => "BUDGET YTD " . $dateParam->format('F Y')
                ],
                "ACHIEVEMENT" => [
                    "label" => "ACHIEVEMENT UP TO"
                ],
                "GROWTH" => [
                    "label" => "GROWTH"
                ],
                "BUDGET_FY_CURRENT" => [
                    "label" => "BUDGET FY " . $dateParam->format('Y')
                ]
            ),
            "excelStyle" => [
                "header" => function($colName) { 
                    return [
                        'font' => [
                            'italic' => false,
                            'bold' => true,
                            'color' => [
                                'rgb' => '000000',
                            ]
                        ],
                        'alignment' => [
                            'horizontal' => 'center',
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => 'thin',
                                'color' => [
                                    'rgb' => '000000',
                                ]
                            ],
                        ],
                    ]; 
                },
                "bottomHeader" => function($colName) {
                    return [
                        
                    ];
                },
                "cell" => function($colName, $value, $row) {
                    if($colName == "GOP_GROUP_DASHBOARD_NAME") {
                        return [
                            'font' => [
                                'italic' => false,
                                'bold' => $row['COLOR'] == "#e8ecdc" ? false : true,
                                'color' => [
                                    'rgb' => $row['COLOR'] == "#081c5c" ? "FFFFFF" : '000000',
                                ]
                            ],
                            'alignment' => [
                                'horizontal' => 'left',
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => 'thin',
                                    'color' => [
                                        'rgb' => '000000',
                                    ]
                                ],
                            ],
                            'fill' => [
                                'fillType' => 'solid', //'solid', 'linear', 'path', 'darkDown', 'darkGray', 'darkGrid', 'darkHorizontal', 'darkTrellis', 'darkUp', 'darkVertical', 'gray0625', 'gray125', 'lightDown', 'lightGray', 'lightGrid', 'lightHorizontal', 'lightTrellis', 'lightUp', 'lightVertical', 'mediumGray'
                                'color' => [
                                    'rgb' => str_replace('#', '', $row['COLOR']),
                                ]
                            ],
                        ];
                    }
                    else if($colName == "ACTUAL_YTD_BACKWARD" || $colName == "ACTUAL_YTD_CURRENT" || $colName == "CURRENT_MONTH_RES" || $colName == "CURRENT_MONTH_SPORT_CLUB" || $colName == "TOTAL_CURRENT_MONTH" || $colName == "BUDGET_YTD_CURRENT" || $colName == "BUDGET_FY_CURRENT") {
                        return [
                            'font' => [
                                'italic' => false,
                                'bold' => $row['COLOR'] == "#e8ecdc" ? false : true,
                                'color' => [
                                    'rgb' => $row['COLOR'] == "#081c5c" ? "FFFFFF" : '000000',
                                ]
                            ],
                            'alignment' => [
                                'horizontal' => 'right',
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => 'thin',
                                    'color' => [
                                        'rgb' => '000000',
                                    ]
                                ],
                            ],
                            'fill' => [
                                'fillType' => 'solid', //'solid', 'linear', 'path', 'darkDown', 'darkGray', 'darkGrid', 'darkHorizontal', 'darkTrellis', 'darkUp', 'darkVertical', 'gray0625', 'gray125', 'lightDown', 'lightGray', 'lightGrid', 'lightHorizontal', 'lightTrellis', 'lightUp', 'lightVertical', 'mediumGray'
                                'color' => [
                                    'rgb' => str_replace('#', '', $row['COLOR']),
                                ]
                            ],
                        ];
                    }
                    else {
                        return [
                            'font' => [
                                'italic' => false,
                                'bold' => $row['COLOR'] == "#e8ecdc" ? false : true,
                                'color' => [
                                    'rgb' => $row['COLOR'] == "#081c5c" ? "FFFFFF" : '000000',
                                ]
                            ],
                            'alignment' => [
                                'horizontal' => 'center',
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => 'thin',
                                    'color' => [
                                        'rgb' => '000000',
                                    ]
                                ],
                            ],
                            'fill' => [
                                'fillType' => 'solid', //'solid', 'linear', 'path', 'darkDown', 'darkGray', 'darkGrid', 'darkHorizontal', 'darkTrellis', 'darkUp', 'darkVertical', 'gray0625', 'gray125', 'lightDown', 'lightGray', 'lightGrid', 'lightHorizontal', 'lightTrellis', 'lightUp', 'lightVertical', 'mediumGray'
                                'color' => [
                                    'rgb' => str_replace('#', '', $row['COLOR']),
                                ]
                            ],
                        ];
                    }
                 },
                "footer" => function($colName, $footerValue) {
                    return [
                        'font' => [
                            'italic' => false,
                            'bold' => true,
                            'color' => [
                                'rgb' => '000000',
                            ]
                        ],
                        'alignment' => [
                            'horizontal' => 'center',
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => 'thin',
                                'color' => [
                                    'rgb' => '000000',
                                ]
                            ],
                        ],
                    ];
                },
            ]
        ));
        ?>
    </div>

    <!-- HEADER -->
    <div cell="D3">
        <?php
            \koolreport\excel\Text::create(array(
                "text" => '',
                "excelStyle" => [
                    'font' => [
                        'name' => 'Calibri',
                        'bold' => true,
                        'italic' => FALSE,
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => [
                                'rgb' => '000000',
                            ]
                        ],
                    ],
                ]
            ));
        ?>
    </div>

    <!-- <div cell="E3">
        <?php
            \koolreport\excel\Text::create(array(
                "text" => '',
                "excelStyle" => [
                    'font' => [
                        'name' => 'Calibri',
                        'bold' => true,
                        'italic' => FALSE,
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => [
                                'rgb' => '000000',
                            ]
                        ],
                    ],
                ]
            ));
        ?>
    </div> -->
    
</div>