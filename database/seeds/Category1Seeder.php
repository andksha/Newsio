<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Category1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = json_decode('[
              {
                "id": 1,
                "parent_id": null,
                "depth": 0,
                "name": "Food & Beverage +",
                "slug": "food-beverage",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 2,
                "parent_id": 1,
                "depth": 1,
                "name": "Food",
                "slug": "food",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 3,
                "parent_id": 2,
                "depth": 2,
                "name": "Dairy Product Manufacturing",
                "slug": "dairy-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 4,
                "parent_id": 2,
                "depth": 2,
                "name": "Animal Food Manufacturing",
                "slug": "animal-food-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 5,
                "parent_id": 2,
                "depth": 2,
                "name": "Grain and Oilseed Milling",
                "slug": "grain-and-oilseed-milling",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 6,
                "parent_id": 2,
                "depth": 2,
                "name": "Sugar and Confectionery Product Manufacturing",
                "slug": "sugar-and-confectionery-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 7,
                "parent_id": 2,
                "depth": 2,
                "name": "Fruit and Vegetable Preserving and Specialty Food Manufacturing",
                "slug": "fruit-and-vegetable-preserving-and-specialty-food-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 8,
                "parent_id": 2,
                "depth": 2,
                "name": "Animal Slaughtering and Processing",
                "slug": "animal-slaughtering-and-processing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 9,
                "parent_id": 2,
                "depth": 2,
                "name": "Seafood Product Preparation and Packaging",
                "slug": "seafood-product-preparation-and-packaging",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 10,
                "parent_id": 2,
                "depth": 2,
                "name": "Bakeries Product Manufacturing",
                "slug": "bakeries-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 11,
                "parent_id": 2,
                "depth": 2,
                "name": "Other Food Manufacturing",
                "slug": "other-food-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 12,
                "parent_id": 1,
                "depth": 1,
                "name": "Beverage",
                "slug": "beverage",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 13,
                "parent_id": 12,
                "depth": 2,
                "name": "Beverage Manufacturing",
                "slug": "beverage-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 14,
                "parent_id": 12,
                "depth": 2,
                "name": "Pure Mineral Water Manufacturing",
                "slug": "pure-mineral-water-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 15,
                "parent_id": null,
                "depth": 0,
                "name": "Apparel & Light Industry +",
                "slug": "apparel-light-industry",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 16,
                "parent_id": 15,
                "depth": 1,
                "name": "Textile Manufacturing",
                "slug": "textile-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 17,
                "parent_id": 16,
                "depth": 2,
                "name": "Fiber, Yarn, and Thread Mills",
                "slug": "fiber-yarn-and-thread-mills",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 18,
                "parent_id": 16,
                "depth": 2,
                "name": "Fabric Mills",
                "slug": "fabric-mills",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 19,
                "parent_id": 16,
                "depth": 2,
                "name": "Textile and Fabric Finishing and Fabric Coating Mills",
                "slug": "textile-and-fabric-finishing-and-fabric-coating-mills",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 20,
                "parent_id": 16,
                "depth": 2,
                "name": "Textile Furnishings Mills",
                "slug": "textile-furnishings-mills",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 21,
                "parent_id": 16,
                "depth": 2,
                "name": "Other Textile Product Mills",
                "slug": "other-textile-product-mills",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 23,
                "parent_id": 15,
                "depth": 1,
                "name": "Apparel Manufacturing",
                "slug": "apparel-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 24,
                "parent_id": 23,
                "depth": 2,
                "name": "Apparel Knitting Mills",
                "slug": "apparel-knitting-mills",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 25,
                "parent_id": 23,
                "depth": 2,
                "name": "Cut and Sew Apparel Manufacturing",
                "slug": "cut-and-sew-apparel-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 26,
                "parent_id": 23,
                "depth": 2,
                "name": "Apparel Accessories and Other Apparel Manufacturing",
                "slug": "apparel-accessories-and-other-apparel-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 27,
                "parent_id": 15,
                "depth": 1,
                "name": "Leather Manufacturing",
                "slug": "leather-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 28,
                "parent_id": 27,
                "depth": 2,
                "name": "Leather and Hide Tanning and Finishing",
                "slug": "leather-and-hide-tanning-and-finishing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 29,
                "parent_id": 27,
                "depth": 2,
                "name": "Footwear Manufacturing",
                "slug": "footwear-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 30,
                "parent_id": 27,
                "depth": 2,
                "name": "Other Leather and Allied Product Manufacturing",
                "slug": "other-leather-and-allied-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 31,
                "parent_id": null,
                "depth": 0,
                "name": "Chemicals+",
                "slug": "chemicals",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 32,
                "parent_id": 31,
                "depth": 1,
                "name": "Oil & Gas Products Manufacturing",
                "slug": "oil-gas-products-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 33,
                "parent_id": 32,
                "depth": 2,
                "name": "Petroleum and Oil Products Manufacturing",
                "slug": "petroleum-and-oil-products-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 34,
                "parent_id": 32,
                "depth": 2,
                "name": "Natural Gas Products Manufacturing",
                "slug": "natural-gas-products-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 35,
                "parent_id": 31,
                "depth": 1,
                "name": "Chemical Products Manufacturing",
                "slug": "chemical-products-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 36,
                "parent_id": 35,
                "depth": 2,
                "name": "Basic Chemical Manufacturing",
                "slug": "basic-chemical-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 37,
                "parent_id": 35,
                "depth": 2,
                "name": "Resin, Synthetic Rubber, and Artificial Synthetic Fibers and Filaments Manufacturing",
                "slug": "resin-synthetic-rubber-and-artificial-synthetic-fibers-and-filaments-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 38,
                "parent_id": 35,
                "depth": 2,
                "name": "Pesticide, Fertilizer, and Other Agricultural Chemical Manufacturing",
                "slug": "pesticide-fertilizer-and-other-agricultural-chemical-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 39,
                "parent_id": 35,
                "depth": 2,
                "name": "Pharmaceutical and Medicine Manufacturing",
                "slug": "pharmaceutical-and-medicine-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 40,
                "parent_id": 35,
                "depth": 2,
                "name": "Paint, Coating, and Adhesive Manufacturing",
                "slug": "paint-coating-and-adhesive-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 41,
                "parent_id": 35,
                "depth": 2,
                "name": "Soap, Cleaning Compound, and Toilet Preparation Manufacturing",
                "slug": "soap-cleaning-compound-and-toilet-preparation-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 42,
                "parent_id": 35,
                "depth": 2,
                "name": "Other Chemical Product and Preparation Manufacturing",
                "slug": "other-chemical-product-and-preparation-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 44,
                "parent_id": 31,
                "depth": 1,
                "name": "Plastics Products Manufacturing",
                "slug": "plastics-products-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 45,
                "parent_id": 44,
                "depth": 2,
                "name": "Plastics Product Manufacturing",
                "slug": "plastics-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 46,
                "parent_id": 44,
                "depth": 2,
                "name": "Rubber Product Manufacturing",
                "slug": "rubber-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 48,
                "parent_id": null,
                "depth": 0,
                "name": "Metallurgy & Mineral",
                "slug": "metallurgy-mineral",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 49,
                "parent_id": 48,
                "depth": 1,
                "name": "Nonmetallic Mineral Product Manufacturing",
                "slug": "nonmetallic-mineral-product-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 50,
                "parent_id": 49,
                "depth": 2,
                "name": "Clay Product and Refractory Manufacturing",
                "slug": "clay-product-and-refractory-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 51,
                "parent_id": 49,
                "depth": 2,
                "name": "Glass and Glass Product Manufacturing",
                "slug": "glass-and-glass-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 52,
                "parent_id": 49,
                "depth": 2,
                "name": "Cement and Concrete Product Manufacturing",
                "slug": "cement-and-concrete-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 53,
                "parent_id": 49,
                "depth": 2,
                "name": "Lime and Gypsum Product Manufacturing",
                "slug": "lime-and-gypsum-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 54,
                "parent_id": 49,
                "depth": 2,
                "name": "Other Nonmetallic Mineral Product Manufacturing",
                "slug": "other-nonmetallic-mineral-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 55,
                "parent_id": 48,
                "depth": 1,
                "name": "Primary Metal Manufacturing",
                "slug": "primary-metal-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 56,
                "parent_id": 55,
                "depth": 2,
                "name": "Iron and Steel Mills and Ferroalloy Manufacturing",
                "slug": "iron-and-steel-mills-and-ferroalloy-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 57,
                "parent_id": 55,
                "depth": 2,
                "name": "Steel Product Manufacturing",
                "slug": "steel-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 58,
                "parent_id": 55,
                "depth": 2,
                "name": "Alumina and Aluminum Production and Processing",
                "slug": "alumina-and-aluminum-production-and-processing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 59,
                "parent_id": 55,
                "depth": 2,
                "name": "Foundries",
                "slug": "foundries",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 61,
                "parent_id": 48,
                "depth": 1,
                "name": "Fabricated Metal Product Manufacturing",
                "slug": "fabricated-metal-product-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 62,
                "parent_id": 61,
                "depth": 2,
                "name": "Forging and Stamping",
                "slug": "forging-and-stamping",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 63,
                "parent_id": 61,
                "depth": 2,
                "name": "Cutlery and Hand tool Manufacturing",
                "slug": "cutlery-and-hand-tool-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 64,
                "parent_id": 61,
                "depth": 2,
                "name": "Architectural and Structural Metals Manufacturing",
                "slug": "architectural-and-structural-metals-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 65,
                "parent_id": 61,
                "depth": 2,
                "name": "Boiler, Tank, and Shipping Container Manufacturing",
                "slug": "boiler-tank-and-shipping-container-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 66,
                "parent_id": 61,
                "depth": 2,
                "name": "Hardware Manufacturing",
                "slug": "hardware-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 67,
                "parent_id": 61,
                "depth": 2,
                "name": "Spring and Wire Product Manufacturing",
                "slug": "spring-and-wire-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 68,
                "parent_id": 61,
                "depth": 2,
                "name": "Machine Shops Product Manufacturing",
                "slug": "machine-shops-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 69,
                "parent_id": 61,
                "depth": 2,
                "name": "Pipe industrial manufacturing (www.upi.ae & algharbiapipe.com)",
                "slug": "pipe-industrial-manufacturing-wwwupiae-algharbiapipecom",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 70,
                "parent_id": 61,
                "depth": 2,
                "name": "Coating, Engraving, Heat Treating, and Allied",
                "slug": "coating-engraving-heat-treating-and-allied",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 71,
                "parent_id": 61,
                "depth": 2,
                "name": "Other Fabricated Metal Product Manufacturing",
                "slug": "other-fabricated-metal-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 73,
                "parent_id": null,
                "depth": 0,
                "name": "Machinery & Parts Manufacturing",
                "slug": "machinery-parts-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 74,
                "parent_id": 73,
                "depth": 1,
                "name": "Agriculture Machinery",
                "slug": "agriculture-machinery",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 75,
                "parent_id": 74,
                "depth": 2,
                "name": "Agriculture",
                "slug": "agriculture",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 76,
                "parent_id": 74,
                "depth": 2,
                "name": "Construction",
                "slug": "construction",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 77,
                "parent_id": 74,
                "depth": 2,
                "name": "and Mining Machinery Manufacturing",
                "slug": "and-mining-machinery-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 78,
                "parent_id": 73,
                "depth": 1,
                "name": "Large Machinery & Equipment",
                "slug": "large-machinery-equipment",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 79,
                "parent_id": 78,
                "depth": 2,
                "name": "Machinery Manufacturing",
                "slug": "machinery-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 80,
                "parent_id": 78,
                "depth": 2,
                "name": "Ventilation, Heating, Air-Conditioning, and Commercial Refrigeration Equipment Manufacturing",
                "slug": "ventilation-heating-air-conditioning-and-commercial-refrigeration-equipment-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 81,
                "parent_id": 78,
                "depth": 2,
                "name": "Metalworking Machinery Manufacturing",
                "slug": "metalworking-machinery-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 82,
                "parent_id": 78,
                "depth": 2,
                "name": "Industrial Machinery Manufacturing",
                "slug": "industrial-machinery-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 83,
                "parent_id": 78,
                "depth": 2,
                "name": "Commercial and Service Industry",
                "slug": "commercial-and-service-industry",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 84,
                "parent_id": 78,
                "depth": 2,
                "name": "Other General-Purpose Machinery Manufacturing",
                "slug": "other-general-purpose-machinery-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 85,
                "parent_id": null,
                "depth": 0,
                "name": "Computer Products",
                "slug": "computer-products",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 86,
                "parent_id": 85,
                "depth": 1,
                "name": "Computer and Electronic Product Manufacturing",
                "slug": "computer-and-electronic-product-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 87,
                "parent_id": 86,
                "depth": 2,
                "name": "Computer and Peripheral Equipment Manufacturing",
                "slug": "computer-and-peripheral-equipment-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 88,
                "parent_id": 86,
                "depth": 2,
                "name": "Communications Equipment Manufacturing",
                "slug": "communications-equipment-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 89,
                "parent_id": 86,
                "depth": 2,
                "name": "Audio and Video Equipment Manufacturing",
                "slug": "audio-and-video-equipment-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 90,
                "parent_id": 85,
                "depth": 1,
                "name": "Computer Components",
                "slug": "computer-components",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 91,
                "parent_id": 90,
                "depth": 2,
                "name": "Semiconductor and Other Electronic Component Manufacturing",
                "slug": "semiconductor-and-other-electronic-component-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 92,
                "parent_id": 90,
                "depth": 2,
                "name": "Navigational, Measuring, Electromedical, and Control Instruments Manufacturing",
                "slug": "navigational-measuring-electromedical-and-control-instruments-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 93,
                "parent_id": 90,
                "depth": 2,
                "name": "Manufacturing and Reproducing Magnetic and Optical Media",
                "slug": "manufacturing-and-reproducing-magnetic-and-optical-media",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 94,
                "parent_id": null,
                "depth": 0,
                "name": "Electrical Components",
                "slug": "electrical-components",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 95,
                "parent_id": 94,
                "depth": 1,
                "name": "Electrical Equipment, Appliance, and Component Manufacturing",
                "slug": "electrical-equipment-appliance-and-component-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 96,
                "parent_id": 95,
                "depth": 2,
                "name": "Electric Lighting Equipment Manufacturing",
                "slug": "electric-lighting-equipment-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 97,
                "parent_id": 95,
                "depth": 2,
                "name": "Household Appliance Manufacturing",
                "slug": "household-appliance-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 98,
                "parent_id": 95,
                "depth": 2,
                "name": "Electrical Equipment Manufacturing",
                "slug": "electrical-equipment-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 99,
                "parent_id": 95,
                "depth": 2,
                "name": "Other Electrical Equipment and Component Manufacturing",
                "slug": "other-electrical-equipment-and-component-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 101,
                "parent_id": 94,
                "depth": 1,
                "name": "Electrical, Control & Telecommunication Cabling Manufacturing",
                "slug": "electrical-control-telecommunication-cabling-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 102,
                "parent_id": 101,
                "depth": 2,
                "name": "Electrical Power Cable Manufacturing",
                "slug": "electrical-power-cable-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 103,
                "parent_id": 101,
                "depth": 2,
                "name": "Instrument and Control Cable Manufacturing",
                "slug": "instrument-and-control-cable-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 104,
                "parent_id": 101,
                "depth": 2,
                "name": "Optical Fiber, Cable & Wire Manufacturing",
                "slug": "optical-fiber-cable-wire-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 105,
                "parent_id": 101,
                "depth": 2,
                "name": "Audio, Video, Coaxial Cable Manufacturing",
                "slug": "audio-video-coaxial-cable-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 106,
                "parent_id": 101,
                "depth": 2,
                "name": "Telecommunication Cable Manufacturing",
                "slug": "telecommunication-cable-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 108,
                "parent_id": null,
                "depth": 0,
                "name": "Transportation Equipment & Vehicle Parts",
                "slug": "transportation-equipment-vehicle-parts",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 109,
                "parent_id": 108,
                "depth": 1,
                "name": "Transportation Equipment Manufacturing",
                "slug": "transportation-equipment-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 110,
                "parent_id": 109,
                "depth": 2,
                "name": "Motor Vehicle Parts Manufacturing",
                "slug": "motor-vehicle-parts-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 111,
                "parent_id": 109,
                "depth": 2,
                "name": "Ship and Boat Building",
                "slug": "ship-and-boat-building",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 112,
                "parent_id": 109,
                "depth": 2,
                "name": "Other Transportation Equipment Manufacturing",
                "slug": "other-transportation-equipment-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 114,
                "parent_id": null,
                "depth": 0,
                "name": "Furniture",
                "slug": "furniture",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 115,
                "parent_id": 114,
                "depth": 1,
                "name": "Furniture and Related Product Manufacturing",
                "slug": "furniture-and-related-product-manufacturing",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 116,
                "parent_id": 115,
                "depth": 2,
                "name": "Household and Institutional Furniture and Kitchen Cabinet Manufacturing",
                "slug": "household-and-institutional-furniture-and-kitchen-cabinet-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 117,
                "parent_id": 115,
                "depth": 2,
                "name": "Office Furniture Manufacturing",
                "slug": "office-furniture-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 118,
                "parent_id": 115,
                "depth": 2,
                "name": "Other Furniture Related Product Manufacturing",
                "slug": "other-furniture-related-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 120,
                "parent_id": null,
                "depth": 0,
                "name": "Building and Decoration",
                "slug": "building-and-decoration",
                "created_at": "2021-04-05 18:53:07",
                "updated_at": "2021-04-05 18:53:07"
              },
              {
                "id": 121,
                "parent_id": 120,
                "depth": 1,
                "name": "Bathroom & Kitchen",
                "slug": "bathroom-kitchen",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 122,
                "parent_id": 121,
                "depth": 2,
                "name": "Bathroom Accessories",
                "slug": "bathroom-accessories",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 123,
                "parent_id": 121,
                "depth": 2,
                "name": "Bathroom Hardware Accessories",
                "slug": "bathroom-hardware-accessories",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 124,
                "parent_id": 121,
                "depth": 2,
                "name": "Faucets",
                "slug": "faucets",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 125,
                "parent_id": 121,
                "depth": 2,
                "name": "Mixers & Taps",
                "slug": "mixers-taps",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 126,
                "parent_id": 121,
                "depth": 2,
                "name": "Kitchen Appliance",
                "slug": "kitchen-appliance",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 127,
                "parent_id": 121,
                "depth": 2,
                "name": "Sauna Room",
                "slug": "sauna-room",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 128,
                "parent_id": 121,
                "depth": 2,
                "name": "Shower Rooms & Accessories",
                "slug": "shower-rooms-accessories",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 129,
                "parent_id": 120,
                "depth": 1,
                "name": "Flooring & Wall & Ceiling Materials",
                "slug": "flooring-wall-ceiling-materials",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 130,
                "parent_id": 129,
                "depth": 2,
                "name": "Ceiling",
                "slug": "ceiling",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 131,
                "parent_id": 129,
                "depth": 2,
                "name": "Ceiling Grid Components",
                "slug": "ceiling-grid-components",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 132,
                "parent_id": 129,
                "depth": 2,
                "name": "Crown Moulding",
                "slug": "crown-moulding",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 133,
                "parent_id": 129,
                "depth": 2,
                "name": "Flooring",
                "slug": "flooring",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 134,
                "parent_id": 129,
                "depth": 2,
                "name": "Mosaics",
                "slug": "mosaics",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 135,
                "parent_id": 129,
                "depth": 2,
                "name": "Skirting",
                "slug": "skirting",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 136,
                "parent_id": 129,
                "depth": 2,
                "name": "Tile",
                "slug": "tile",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 137,
                "parent_id": 129,
                "depth": 2,
                "name": "Tile Accessories",
                "slug": "tile-accessories",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 138,
                "parent_id": 129,
                "depth": 2,
                "name": "Walls & Accessories",
                "slug": "walls-accessories",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 139,
                "parent_id": 120,
                "depth": 1,
                "name": "Door, Window & Accessories",
                "slug": "door-window-accessories",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 140,
                "parent_id": 139,
                "depth": 2,
                "name": "Door",
                "slug": "door",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 141,
                "parent_id": 139,
                "depth": 2,
                "name": "Door & Window Accessories",
                "slug": "door-window-accessories",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 142,
                "parent_id": 139,
                "depth": 2,
                "name": "Gate",
                "slug": "gate",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 143,
                "parent_id": 139,
                "depth": 2,
                "name": "Locks",
                "slug": "locks",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 144,
                "parent_id": 139,
                "depth": 2,
                "name": "Window",
                "slug": "window",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 145,
                "parent_id": 120,
                "depth": 1,
                "name": "Construction Material Manufacturing",
                "slug": "construction-material-manufacturing",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 146,
                "parent_id": 145,
                "depth": 2,
                "name": "Building Finishing & Structural Materials",
                "slug": "building-finishing-structural-materials",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 147,
                "parent_id": 145,
                "depth": 2,
                "name": "Sand & Stone",
                "slug": "sand-stone",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 148,
                "parent_id": 145,
                "depth": 2,
                "name": "Related products",
                "slug": "related-products",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 149,
                "parent_id": 120,
                "depth": 1,
                "name": "Paper Manufacturing",
                "slug": "paper-manufacturing",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 150,
                "parent_id": 149,
                "depth": 2,
                "name": "Pulp, Paper, and Paperboard Mills",
                "slug": "pulp-paper-and-paperboard-mills",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 151,
                "parent_id": 149,
                "depth": 2,
                "name": "Converted Paper Product Manufacturing",
                "slug": "converted-paper-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 152,
                "parent_id": 120,
                "depth": 1,
                "name": "Wood Product Manufacturing",
                "slug": "wood-product-manufacturing",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 153,
                "parent_id": 152,
                "depth": 2,
                "name": "Veneer, Plywood, and Engineered Wood Product Manufacturing",
                "slug": "veneer-plywood-and-engineered-wood-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 154,
                "parent_id": 152,
                "depth": 2,
                "name": "Other Wood Product Manufacturing",
                "slug": "other-wood-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 155,
                "parent_id": null,
                "depth": 0,
                "name": "Health & Medicine",
                "slug": "health-medicine",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 156,
                "parent_id": 155,
                "depth": 1,
                "name": "Health and Hospital Product Manufacturing",
                "slug": "health-and-hospital-product-manufacturing",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 157,
                "parent_id": 156,
                "depth": 2,
                "name": "Medical Supplies",
                "slug": "medical-supplies",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 158,
                "parent_id": 156,
                "depth": 2,
                "name": "Medical Equipment",
                "slug": "medical-equipment",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 159,
                "parent_id": null,
                "depth": 0,
                "name": "Sports and Recreation",
                "slug": "sports-and-recreation",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 160,
                "parent_id": 159,
                "depth": 1,
                "name": "Sport Product Manufacturing",
                "slug": "sport-product-manufacturing",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 161,
                "parent_id": 160,
                "depth": 2,
                "name": "Indoor Sports & Fitness Manufacturing",
                "slug": "indoor-sports-fitness-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 162,
                "parent_id": 160,
                "depth": 2,
                "name": "Outdoor Sports & Facilities Manufacturing",
                "slug": "outdoor-sports-facilities-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 163,
                "parent_id": 160,
                "depth": 2,
                "name": "Recreation Supplies Manufacturing",
                "slug": "recreation-supplies-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 164,
                "parent_id": 159,
                "depth": 1,
                "name": "Miscellaneous Manufacturing",
                "slug": "miscellaneous-manufacturing",
                "created_at": "2021-04-05 18:53:08",
                "updated_at": "2021-04-05 18:53:08"
              },
              {
                "id": 165,
                "parent_id": 164,
                "depth": 2,
                "name": "Medical Equipment and Supplies Manufacturing",
                "slug": "medical-equipment-and-supplies-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 166,
                "parent_id": 164,
                "depth": 2,
                "name": "Tent and Shade Manufacturing",
                "slug": "tent-and-shade-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 167,
                "parent_id": 164,
                "depth": 2,
                "name": "Picnic Product Manufacturing",
                "slug": "picnic-product-manufacturing",
                "created_at": null,
                "updated_at": null
              },
              {
                "id": 168,
                "parent_id": 164,
                "depth": 2,
                "name": "Other Miscellaneous Manufacturing",
                "slug": "other-miscellaneous-manufacturing",
                "created_at": null,
                "updated_at": null
              }
            ]', true);

        DB::table('categories1')->insert($data);
    }
}
