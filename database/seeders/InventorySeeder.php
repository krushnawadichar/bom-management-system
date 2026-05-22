<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $inventory = [
            // ==================== SHELL ASSEMBLY ITEMS (from BOM_REV_1.xls) ====================
            [
                'item_code' => 'SA516GR70-820X12-2480',
                'item_name' => 'Shell Pipe - 1',
                'description' => 'SA 516 Gr.70 OD 820 x 12 THK x 2480 LG',
                'material_grade' => 'SA 516 Gr.70',
                'uom' => 'NOS',
                'quantity_on_hand' => 10,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 2,
                'location' => 'Warehouse A',
                'supplier_name' => 'Steel Industries Ltd'
            ],
            [
                'item_code' => 'SA516GR70-820X12-1160',
                'item_name' => 'Shell Pipe - 2',
                'description' => 'SA 516 Gr.70 OD 820 x 12 THK x 1160 LG',
                'material_grade' => 'SA 516 Gr.70',
                'uom' => 'NOS',
                'quantity_on_hand' => 8,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 2,
                'location' => 'Warehouse A',
                'supplier_name' => 'Steel Industries Ltd'
            ],
            [
                'item_code' => 'P23.02914.SFSS.R1',
                'item_name' => 'Shell Flange Stationary Side',
                'description' => 'SA 105 OD 952 x ID 822 x 80 THK',
                'material_grade' => 'SA 105',
                'uom' => 'NOS',
                'quantity_on_hand' => 15,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 3,
                'location' => 'Warehouse B',
                'supplier_name' => 'Flange Manufacturers Inc'
            ],
            [
                'item_code' => 'P23.02914.SFFS.R1',
                'item_name' => 'Shell Flange Floating Side',
                'description' => 'SA 105 OD 952 x ID 796 x 70 THK',
                'material_grade' => 'SA 105',
                'uom' => 'NOS',
                'quantity_on_hand' => 12,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 3,
                'location' => 'Warehouse B',
                'supplier_name' => 'Flange Manufacturers Inc'
            ],
            [
                'item_code' => 'SA106GRB-6IN-SCH40',
                'item_name' => 'Nozzle Pipe 6"',
                'description' => 'SA 106 Gr.B 6"NB x SCH.40 (7.11 THK)',
                'material_grade' => 'SA 106 Gr.B',
                'uom' => 'NOS',
                'quantity_on_hand' => 50,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 10,
                'location' => 'Warehouse A',
                'supplier_name' => 'Pipe Suppliers Co'
            ],
            [
                'item_code' => 'SA105-FLANGE-6IN-CL150',
                'item_name' => 'Nozzle Flange 6" CL150',
                'description' => 'SA 105 6"NB ASME B16.5 CL 150 WNRF SCH.40',
                'material_grade' => 'SA 105',
                'uom' => 'NOS',
                'quantity_on_hand' => 35,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 8,
                'location' => 'Warehouse B',
                'supplier_name' => 'Flange Manufacturers Inc'
            ],
            [
                'item_code' => 'SA106GRB-075IN-SCH160',
                'item_name' => 'Nozzle Pipe 3/4"',
                'description' => 'SA 106 Gr.B 3/4"NB x SCH.160 (5.56 THK)',
                'material_grade' => 'SA 106 Gr.B',
                'uom' => 'NOS',
                'quantity_on_hand' => 100,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 20,
                'location' => 'Warehouse A',
                'supplier_name' => 'Pipe Suppliers Co'
            ],
            [
                'item_code' => 'SA105-FLANGE-075IN-CL150',
                'item_name' => 'Nozzle Flange 3/4" CL150',
                'description' => 'SA 105 3/4"NB ASME B16.5 CL 150 WNRF SCH.160',
                'material_grade' => 'SA 105',
                'uom' => 'NOS',
                'quantity_on_hand' => 60,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 15,
                'location' => 'Warehouse B',
                'supplier_name' => 'Flange Manufacturers Inc'
            ],
            [
                'item_code' => 'SA234WPB-ELBOW-6IN',
                'item_name' => 'Elbow 6" 90 DEG LR',
                'description' => 'SA 234 Gr.WPB 6"NB x SCH.40 90 DEG LR',
                'material_grade' => 'SA 234 Gr.WPB',
                'uom' => 'NOS',
                'quantity_on_hand' => 25,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 5,
                'location' => 'Warehouse C',
                'supplier_name' => 'Fittings Corp'
            ],
            [
                'item_code' => 'SA234WPB-ELBOW-075IN',
                'item_name' => 'Elbow 3/4" 90 DEG LR',
                'description' => 'SA 234 Gr.WPB 3/4"NB x SCH.160 90 DEG LR',
                'material_grade' => 'SA 234 Gr.WPB',
                'uom' => 'NOS',
                'quantity_on_hand' => 40,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 10,
                'location' => 'Warehouse C',
                'supplier_name' => 'Fittings Corp'
            ],

            // ==================== TUBE BUNDLE ITEMS ====================
            [
                'item_code' => 'P23.02914.TSSS.R1',
                'item_name' => 'Tube Sheet Stationary Side',
                'description' => 'SA 240 TYPE 316L OD 856 x 46 THK',
                'material_grade' => 'SA 240 TYPE 316L',
                'uom' => 'NOS',
                'quantity_on_hand' => 5,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 2,
                'location' => 'Warehouse D',
                'supplier_name' => 'Stainless Steel Specialists'
            ],
            [
                'item_code' => 'P23.02914.TSFS.R1',
                'item_name' => 'Tube Sheet Floating Side',
                'description' => 'SA 240 TYPE 316L OD 791 x 46 THK',
                'material_grade' => 'SA 240 TYPE 316L',
                'uom' => 'NOS',
                'quantity_on_hand' => 5,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 2,
                'location' => 'Warehouse D',
                'supplier_name' => 'Stainless Steel Specialists'
            ],
            [
                'item_code' => 'SA213-TP316L-20X2-6290',
                'item_name' => 'SS 316L Tube 20mm',
                'description' => 'SA 213 Gr.TP316L 20 OD x 2 THK x 6290 LG Seamless',
                'material_grade' => 'SA 213 TP316L',
                'uom' => 'NOS',
                'quantity_on_hand' => 800,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 200,
                'location' => 'Warehouse A',
                'supplier_name' => 'Tube Manufacturers Ltd'
            ],
            [
                'item_code' => 'SA516GR70-BAFFLE-791',
                'item_name' => 'Baffles',
                'description' => 'SA 516 Gr.70 OD 791 x 6 THK',
                'material_grade' => 'SA 516 Gr.70',
                'uom' => 'NOS',
                'quantity_on_hand' => 60,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 15,
                'location' => 'Warehouse E',
                'supplier_name' => 'Plate Suppliers Inc'
            ],

            // ==================== FASTENERS ====================
            [
                'item_code' => 'SA193B7-STUD-075UNC-250',
                'item_name' => 'Stud 3/4" UNC x 250',
                'description' => 'SA 193 Gr.B7 3/4" UNC x 250 LG',
                'material_grade' => 'SA 193 Gr.B7',
                'uom' => 'NOS',
                'quantity_on_hand' => 200,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 50,
                'location' => 'Hardware Store',
                'supplier_name' => 'Fasteners World'
            ],
            [
                'item_code' => 'SA1942H-NUT-075UNC',
                'item_name' => 'Heavy Hex Nut 3/4" UNC',
                'description' => 'SA 193 Gr.2H 3/4" UNC',
                'material_grade' => 'SA 193 Gr.2H',
                'uom' => 'NOS',
                'quantity_on_hand' => 800,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 150,
                'location' => 'Hardware Store',
                'supplier_name' => 'Fasteners World'
            ],
            [
                'item_code' => 'SPRING-WASHER-075UNC',
                'item_name' => 'Spring Washer 3/4" UNC',
                'description' => 'Carbon Steel 3/4" UNC Spring Washer',
                'material_grade' => 'Carbon Steel',
                'uom' => 'NOS',
                'quantity_on_hand' => 800,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 150,
                'location' => 'Hardware Store',
                'supplier_name' => 'Fasteners World'
            ],

            // ==================== BOM_REV_2.xlsx ITEMS (OIL COOLER) ====================
            [
                'item_code' => 'SA240-316L-1858X402X37',
                'item_name' => 'Tube Sheet for Machining',
                'description' => 'SA-240 TYPE 316/316L DUAL CERTIFIED 1858 X 402 X 37 THK',
                'material_grade' => 'SA-240 TYPE 316/316L',
                'uom' => 'NOS',
                'quantity_on_hand' => 4,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 2,
                'location' => 'Warehouse D',
                'supplier_name' => 'Stainless Steel Specialists'
            ],
            [
                'item_code' => 'P25.267.TPH',
                'item_name' => 'Top Plate Header',
                'description' => 'SA-240 TYPE 316/316L 920 X 200 X 16 THK',
                'material_grade' => 'SA-240 TYPE 316/316L',
                'uom' => 'NOS',
                'quantity_on_hand' => 8,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 2,
                'location' => 'Warehouse D',
                'supplier_name' => 'Stainless Steel Specialists'
            ],
            [
                'item_code' => 'P25.267.EFTT',
                'item_name' => 'Finned Tube',
                'description' => 'SA-213 TP 316/316L + Aluminium Alloy ASTM B221',
                'material_grade' => 'SA-213 TP 316/316L',
                'uom' => 'NOS',
                'quantity_on_hand' => 200,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 50,
                'location' => 'Warehouse A',
                'supplier_name' => 'Tube Manufacturers Ltd'
            ],
            [
                'item_code' => 'SA182-F316L-FLANGE-2IN',
                'item_name' => 'Nozzle Flange 2" Class 600',
                'description' => 'SA-182 GR.F 316/316L DN 50 (2" NPS) CLASS 600 WNRF',
                'material_grade' => 'SA-182 GR.F 316/316L',
                'uom' => 'NOS',
                'quantity_on_hand' => 20,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 5,
                'location' => 'Warehouse B',
                'supplier_name' => 'Flange Manufacturers Inc'
            ],
            [
                'item_code' => 'SA193B8M-STUD-058UNC',
                'item_name' => 'Stud 5/8" UNC B8M',
                'description' => 'SA-193 GR B8M CL.1 5/8"UNC x 130 LG',
                'material_grade' => 'SA-193 GR B8M',
                'uom' => 'NOS',
                'quantity_on_hand' => 150,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 30,
                'location' => 'Hardware Store',
                'supplier_name' => 'Fasteners World'
            ],
            [
                'item_code' => 'SS316L-BALL-VALVE-2IN',
                'item_name' => '2" Ball Valve SS316L',
                'description' => 'SS 316L DN 50 (2" NPS) FLANGE TYPE RF CLASS 600',
                'material_grade' => 'SS 316L',
                'uom' => 'NOS',
                'quantity_on_hand' => 8,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 2,
                'location' => 'Warehouse F',
                'supplier_name' => 'Valve Suppliers Inc'
            ],
            [
                'item_code' => 'SA516GR70-C-CHANNEL',
                'item_name' => 'C-Channel',
                'description' => 'ISMC 250 Carbon Steel Channel',
                'material_grade' => 'IS 2062 E250 BR',
                'uom' => 'NOS',
                'quantity_on_hand' => 30,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 5,
                'location' => 'Warehouse E',
                'supplier_name' => 'Structural Steel Co'
            ],
            [
                'item_code' => 'GR88-HEX-BOLT-M20',
                'item_name' => 'Hex Bolt M20 Grade 8.8',
                'description' => 'GR 8.8 M20 Hex Bolt',
                'material_grade' => 'GR 8.8',
                'uom' => 'NOS',
                'quantity_on_hand' => 500,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 100,
                'location' => 'Hardware Store',
                'supplier_name' => 'Fasteners World'
            ],
            [
                'item_code' => 'SA516GR70-LIFTING-LUG',
                'item_name' => 'Lifting Lug',
                'description' => 'SA 516 Gr.70 Lifting Lug Assembly',
                'material_grade' => 'SA 516 Gr.70',
                'uom' => 'NOS',
                'quantity_on_hand' => 25,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 5,
                'location' => 'Warehouse E',
                'supplier_name' => 'Fabrication Specialists'
            ],

            // ==================== INSTRUMENTS & SPECIAL ITEMS ====================
            [
                'item_code' => 'PRESSURE-GAUGE-2.1KG',
                'item_name' => 'Pressure Gauge',
                'description' => 'Pressure Gauge 0 to 2.1 Kg/cm2 Dial 2"',
                'material_grade' => 'Stainless Steel',
                'uom' => 'NOS',
                'quantity_on_hand' => 10,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 3,
                'location' => 'Instrument Store',
                'supplier_name' => 'Instrumentation Ltd'
            ],
            [
                'item_code' => '2WAY-BALL-VALVE-14BSP',
                'item_name' => '2-Way Ball Valve 1/4" BSP',
                'description' => '2-Way Ball Valve G1/4" BSP Threaded',
                'material_grade' => 'SS-BODY, SS-INTERNAL',
                'uom' => 'NOS',
                'quantity_on_hand' => 15,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 5,
                'location' => 'Warehouse F',
                'supplier_name' => 'Valve Suppliers Inc'
            ],

            // ==================== GASKETS & SEALS ====================
            [
                'item_code' => 'METAL-JACKET-GASKET-856',
                'item_name' => 'Metal Jacket Gasket',
                'description' => 'Metal Jacket Gasket OD 856 x ID 830 x 3.2 THK',
                'material_grade' => 'Metal Jacket + Fiber Iron',
                'uom' => 'NOS',
                'quantity_on_hand' => 20,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 5,
                'location' => 'Warehouse G',
                'supplier_name' => 'Gasket Manufacturers'
            ],
            [
                'item_code' => 'VITON-ORING-786',
                'item_name' => 'Viton O-Ring',
                'description' => 'Viton O-Ring 786 ID x 8 CSD',
                'material_grade' => 'Viton',
                'uom' => 'NOS',
                'quantity_on_hand' => 30,
                'quantity_reserved' => 0,
                'minimum_stock_level' => 10,
                'location' => 'Warehouse G',
                'supplier_name' => 'Seal Suppliers Inc'
            ],

            // ==================== LOW STOCK ITEMS (To trigger purchase intents) ====================
            [
                'item_code' => 'SA516GR70-820X12-175',
                'item_name' => 'Shell Pipe Stationary Side',
                'description' => 'SA 516 Gr.70 OD 820 x 12 THK x 175 LG',
                'material_grade' => 'SA 516 Gr.70',
                'uom' => 'NOS',
                'quantity_on_hand' => 1, // Low stock - will trigger purchase intent
                'quantity_reserved' => 0,
                'minimum_stock_level' => 5,
                'location' => 'Warehouse A',
                'supplier_name' => 'Steel Industries Ltd'
            ],
            [
                'item_code' => 'SA240-316L-TUBE-SHEET',
                'item_name' => 'Tube Sheet Material',
                'description' => 'SA 240 TYPE 316L for Tube Sheet',
                'material_grade' => 'SA 240 TYPE 316L',
                'uom' => 'NOS',
                'quantity_on_hand' => 0, // Out of stock
                'quantity_reserved' => 0,
                'minimum_stock_level' => 3,
                'location' => 'Warehouse D',
                'supplier_name' => 'Stainless Steel Specialists'
            ],
            [
                'item_code' => 'SA193B7-STUD-M24',
                'item_name' => 'Stud M24',
                'description' => 'SA 193 Gr.B7 M24 x 115 LG',
                'material_grade' => 'SA 193 Gr.B7',
                'uom' => 'NOS',
                'quantity_on_hand' => 2, // Low stock
                'quantity_reserved' => 0,
                'minimum_stock_level' => 10,
                'location' => 'Hardware Store',
                'supplier_name' => 'Fasteners World'
            ],
        ];
        
        foreach ($inventory as $item) {
            // Check if item already exists
            $existing = Inventory::where('item_code', $item['item_code'])->first();
            if (!$existing) {
                Inventory::create($item);
                $this->command->info("Created inventory item: {$item['item_code']}");
            } else {
                $this->command->warn("Inventory item already exists: {$item['item_code']}");
            }
        }
        
        $this->command->info('Inventory seeder completed!');
    }
}