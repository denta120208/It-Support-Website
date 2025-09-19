<?php
    use \koolreport\excel\Table;
    use \koolreport\excel\PivotTable;
    use \koolreport\excel\BarChart;
    use \koolreport\excel\LineChart;

    $sheet1 = "Promosi Expense";
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

    <div cell="A3">
        <?php
        $dateParam = DateTime::createFromFormat('Ym', $this->cut_off);
        Table::create(array(
            "dataSource" => $this->dataStore('finance_accounting_promosi_expense_excel'),
            // "showFooter"=>"false",
            "columns" => array(
                "PROMOSI_GROUP_DASHBOARD_NAME" => [
                    "label" => "KETERANGAN"
                ],
                "ACTUAL_YTD_BACKWARD" => [
                    "label" => "ACTUAL YTD " . $dateParam->format('F') . ' ' . ($dateParam->format('Y') - 1)
                ],
                "ACTUAL_YTD_CURRENT" => [
                    "label" => "ACTUAL YTD " . $dateParam->format('F Y')
                ],
                "BUDGET_YTD_CURRENT" => [
                    "label" => "BUDGET YTD " . $dateParam->format('F Y')
                ],
                "PERCENT_TO_NET_SALES" => [
                    "label" => "% TERHADAP NET SALES"
                ],
                "PERCENT_TO_GROSS_SALES" => [
                    "label" => "% TERHADAP GROSS SALES"
                ],
                "ACHIEVEMENT" => [
                    "label" => "ACHIEVEMENT"
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
                    if($colName == "PROMOSI_GROUP_DASHBOARD_NAME") {
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
                    else if($colName == "ACTUAL_YTD_BACKWARD" || $colName == "ACTUAL_YTD_CURRENT" || $colName == "BUDGET_YTD_CURRENT" || $colName == "BUDGET_FY_CURRENT") {
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
    
</div>