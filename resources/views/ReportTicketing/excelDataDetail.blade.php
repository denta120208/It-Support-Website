<?php
    use \koolreport\excel\Table;
    use \koolreport\excel\PivotTable;
    use \koolreport\excel\BarChart;
    use \koolreport\excel\LineChart;

    $sheet1 = "Data Reporting Detail";
?>
<meta charset="UTF-8">

<?php if(count($this->dataStore('reporting_detail1')->all()) > 0) { ?>
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

    <?php
        $dataProject = DB::select("SELECT * FROM MD_PROJECT AS a WHERE a.PROJECT_NO_CHAR = '".$this->project_param."'");
    ?>

    <div range="D1:G1">
        <?php
            \koolreport\excel\Text::create(array(
                "text" => "Data Reporting Detail",
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
                            'borderStyle' => 'none',
                            'color' => [
                                'rgb' => '000000',
                            ]
                        ],
                    ],
                ]
            ));
        ?>
    </div>
    <div range="D2:G2">
        <?php
            \koolreport\excel\Text::create(array(
                "text" => $dataProject[0]->PROJECT_NAME,
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
                            'borderStyle' => 'none',
                            'color' => [
                                'rgb' => '000000',
                            ]
                        ],
                    ],
                ]
            ));
        ?>
    </div>
    <div range="D3:G3">
        <?php
        \koolreport\excel\Text::create(array(
            "text" => date('d/m/Y', strtotime($this->start_date_param)) . " - " . date('d/m/Y', strtotime($this->end_date_param)),
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
                        'borderStyle' => 'none',
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                ],
            ]
        ));
        ?>
    </div>
    <div range="D4:G4">
        <?php
        \koolreport\excel\Text::create(array(
            "text" => "Printed by " . Session::get('name') . " at " . date('d/m/Y H:i'),
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
                        'borderStyle' => 'none',
                        'color' => [
                            'rgb' => '000000',
                        ]
                    ],
                ],
            ]
        ));
        ?>
    </div>
    <div cell="A6">
    <?php
        Table::create(array(
            "dataSource" => $this->dataStore('reporting_detail1'),
            "showFooter"=>"true",
            "columns"=>array(
                "TGL_REQUEST" => ["label" => "Tgl Request", "formatValue" => function($value, $row) {return date('d-m-Y', strtotime($value)); }, "footerText" => "-"],
                "JAM_BERANGKAT" => ["label" => "Jam Berangkat", "formatValue" => function($value, $row) { return $value; }, "footerText" => "-"],
                "REAL_JAM_PULANG" => ["label" => "Jam Pulang", "formatValue" => function($value, $row) { return $value; }, "footerText" => "-"],
                "NAMA_PENYEWA" => ["label" => "Nama Penyewa", "formatValue" => function($value, $row) { return $value; }, "footerText" => "-"],
                "TUJUAN" => ["label" => "Tujuan", "formatValue" => function($value, $row) {  return $value; }, "footerText" => "-"],
                "KETERANGAN" => ["label" => "Keterangan", "formatValue" => function($value, $row) { return $value; }, "footerText" => "-"],
                "PROJECT_NAME" => ["label" => "Kantor", "formatValue" => function($value, $row) { return $value; }, "footerText" => "-"],
                "nama" => ["label" => "Nama Driver", "formatValue" => function($value, $row) { return $value; }, "footerText" => "-"], 
                "platno" => ["label" => " Plat No", "formatValue" => function($value, $row) { return $value; }, "footerText" => "-"],
                "merek" => ["label" => "Merek Mobil", "formatValue" => function($value, $row) { return $value; }, "footerText" => "-"],
                "KM_AWAL" => ["label" => "Km Awal", "formatValue" => function($value, $row) { return $value; }, "footerText" => "-"],
                "KM_AKHIR" => ["label" => "Km Akhir", "formatValue" => function($value, $row) { return $value; }, "footerText" => "-"],
                "nominal_pemakaian" => ["label" => "Nominal Pemakaian (Rp)", "formatValue" => function($value, $row) { return number_format($value,0,',','.'); }, "footer" => "sum", "footerText" => "@value"],
                // "INVOICE_TRANS_PPN" => ["label" => "PPN", "formatValue" => function($value, $row) { return number_format($value,0,',','.'); }, "footer" => "sum", "footerText" => "@value"],
                // "INVOICE_TRANS_PPH" => ["label" => "PPH", "formatValue" => function($value, $row) { return number_format($value,0,',','.'); }, "footer" => "sum", "footerText" => "@value"],
                // "INVOICE_TRANS_TOTAL" => ["label" => "Total Invoice", "formatValue" => function($value, $row) { return number_format($value,0,',','.'); }, "footer" => "sum", "footerText" => "@value"],
                // "PAID_BILL_AMOUNT" => ["label" => "Paid Amount", "formatValue" => function($value, $row) { return number_format($value,0,',','.'); }, "footer" => "sum", "footerText" => "@value"]
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
                    if($colName == "TGL_REQUEST") {
                        return [
                            'font' => [
                                'italic' => false,
                                'color' => [
                                    'rgb' => '000000',
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
                        ];
                    }
                    else if($colName == "JAM_BERANGKAT" || $colName == "REAL_JAM_PULANG") {
                        return [
                            'font' => [
                                'italic' => false,
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
                    }
                    else if($colName == "NAMA_PENYEWA" || $colName == "TUJUAN" || $colName == "KETERANGAN" || $colName == "PROJECT_NAME" || $colName == "nama" || $colName == "platno" || $colName == "merek" || $colName == "KM_AWAL" || $colName == "KM_AKHIR" ||$colName == "nominal_pemakaian") {
                        return [
                            'font' => [
                                'italic' => false,
                                'color' => [
                                    'rgb' => '000000',
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
                        ];
                    }
                    else {
                        return [
                            'font' => [
                                'italic' => false,
                                'color' => [
                                    'rgb' => '000000',
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
<?php } ?>