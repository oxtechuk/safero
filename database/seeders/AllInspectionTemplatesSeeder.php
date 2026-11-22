<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class AllInspectionTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = $this->getTemplatesData();

        foreach ($templates as $item) {
            CertificateTemplate::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function getTemplatesData(): array
    {
        // 1. Crane Checklist (15 items)
        $craneChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Structural members (girders, end carriages, jib, mast, boom)', 'question' => 'Structural members (girders, end carriages, jib, mast, boom)', 'acceptance_criteria' => "No visible cracks, distortion, corrosion, or permanent deformation beyond manufacturer's tolerance", 'rejection_criteria' => 'Cracks, buckling, excessive corrosion, or deformation affecting structural integrity', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Welded / bolted connections', 'question' => 'Welded / bolted connections', 'acceptance_criteria' => 'All welds sound; bolts present, correctly torqued, no shear or elongation', 'rejection_criteria' => 'Cracked welds, missing/loose/sheared fasteners', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'Rail track / runway (if applicable)', 'question' => 'Rail track / runway (if applicable)', 'acceptance_criteria' => 'Rails secure, aligned, end stops fitted and intact', 'rejection_criteria' => 'Rail misalignment, worn/missing end stops, loose fixings', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Hoist rope / chain', 'question' => 'Hoist rope / chain', 'acceptance_criteria' => 'No broken wires beyond permissible limit, no kinks, corrosion, or wear beyond discard criteria', 'rejection_criteria' => 'Broken wires exceeding discard criteria, kinking, birdcaging, severe corrosion', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '5.2*', 'item' => 'Drum / sheaves / pulleys', 'question' => 'Drum / sheaves / pulleys', 'acceptance_criteria' => 'Free from cracks, correct grooving, rope correctly spooled', 'rejection_criteria' => 'Cracked or worn grooves, rope mis-spooling, seized bearings', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '5.3*', 'item' => 'Hook block and safety latch', 'question' => 'Hook block and safety latch', 'acceptance_criteria' => "Hook not exceeding manufacturer's throat-opening / twist tolerance, safety catch functional", 'rejection_criteria' => 'Throat opening or twist exceeds tolerance, missing/inoperative safety catch, visible cracks', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 7, 'clause_ref' => '6.1*', 'item' => 'Brakes (hoist, travel, slew)', 'question' => 'Brakes (hoist, travel, slew)', 'acceptance_criteria' => "Holds rated load without slip or drift; adjusted per manufacturer's specification", 'rejection_criteria' => 'Brake slip, drift, or failure to hold rated load', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 8, 'clause_ref' => '6.2*', 'item' => 'Limit switches (upper/lower hoist, travel, slew)', 'question' => 'Limit switches (upper/lower hoist, travel, slew)', 'acceptance_criteria' => 'All limit switches operate at correct set point and de-energise motion', 'rejection_criteria' => 'Limit switch inoperative, mis-set, or bypassed', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 9, 'clause_ref' => '6.3*', 'item' => 'Overload / load-moment indicator (where fitted)', 'question' => 'Overload / load-moment indicator (where fitted)', 'acceptance_criteria' => 'Indicator calibrated and functions correctly at rated capacity', 'rejection_criteria' => 'Indicator not functioning, uncalibrated, or bypassed', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 10, 'clause_ref' => '7.1*', 'item' => 'Electrical system / control pendant / radio control', 'question' => 'Electrical system / control pendant / radio control', 'acceptance_criteria' => 'Insulation intact, controls correctly labelled, emergency stop functional', 'rejection_criteria' => 'Damaged insulation/cabling, non-functional emergency stop, unlabelled controls', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 11, 'clause_ref' => '7.2*', 'item' => 'Guarding of moving parts', 'question' => 'Guarding of moving parts', 'acceptance_criteria' => 'All guards fitted, secure, and undamaged', 'rejection_criteria' => 'Missing, damaged, or improperly secured guards', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 12, 'clause_ref' => '8.1*', 'item' => 'SWL / rated capacity marking', 'question' => 'SWL / rated capacity marking', 'acceptance_criteria' => 'Legible and consistent with load chart / duty class', 'rejection_criteria' => 'Illegible, missing, or inconsistent capacity marking', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 13, 'clause_ref' => '9.1*', 'item' => 'Function test under no load', 'question' => 'Function test under no load', 'acceptance_criteria' => 'Smooth operation through full range of motion, all motions and safety devices function correctly', 'rejection_criteria' => 'Erratic operation, unusual noise/vibration, failure of any safety device', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 14, 'clause_ref' => '9.2*', 'item' => 'Static / dynamic load test (post-installation, post-repair, or per scheme of examination)', 'question' => 'Static / dynamic load test', 'acceptance_criteria' => "No permanent deformation; deflection within manufacturer's allowable limit after test load removed", 'rejection_criteria' => 'Permanent deformation, deflection outside allowable limit, or structural distress under test', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 15, 'clause_ref' => '10.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => "Manufacturer's declaration of conformity, prior examination reports, and maintenance records available and in order", 'rejection_criteria' => 'Missing or incomplete documentation record', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        // 2. Hoists & Winch Checklist (12 items)
        $hoistChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Body / housing / suspension mount', 'question' => 'Body / housing / suspension mount', 'acceptance_criteria' => 'No cracks, distortion, or corrosion; suspension point secure', 'rejection_criteria' => 'Cracks, deformation, or corrosion affecting integrity; loose suspension', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Load chain / wire rope', 'question' => 'Load chain / wire rope', 'acceptance_criteria' => 'No stretch beyond discard criteria, no cracked/worn links, correct lubrication', 'rejection_criteria' => 'Chain stretch beyond discard limit, cracked links, corrosion, insufficient lubrication', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'Bottom hook and top hook / suspension hook', 'question' => 'Bottom hook and top hook / suspension hook', 'acceptance_criteria' => 'Safety latch present and functional; throat opening within tolerance; no twist', 'rejection_criteria' => 'Missing/damaged safety latch, throat opening or twist beyond tolerance, visible cracks', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Load sheave / gear train', 'question' => 'Load sheave / gear train', 'acceptance_criteria' => 'Correct pocket profile, no excessive wear, smooth gear engagement', 'rejection_criteria' => 'Worn sheave pockets causing chain jump, gear wear/damage', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '5.2*', 'item' => 'Braking mechanism', 'question' => 'Braking mechanism', 'acceptance_criteria' => 'Holds rated load with no slip or creep', 'rejection_criteria' => 'Load slip, drift, or brake failure under rated load', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '6.1*', 'item' => 'Hand chain / lever mechanism (manual units)', 'question' => 'Hand chain / lever mechanism (manual units)', 'acceptance_criteria' => 'Free operation, no bent links or seized pivots', 'rejection_criteria' => 'Bent hand chain, seized or worn lever mechanism', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 7, 'clause_ref' => '6.2*', 'item' => 'Electric motor and control (powered units)', 'question' => 'Electric motor and control (powered units)', 'acceptance_criteria' => 'Motor insulation sound, controls correctly labelled, limit switches functional', 'rejection_criteria' => 'Damaged wiring, missing/faulty limit switches, non-functional emergency stop', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 8, 'clause_ref' => '7.1*', 'item' => 'Upper and lower limit devices', 'question' => 'Upper and lower limit devices', 'acceptance_criteria' => 'Correctly set, stops motion before mechanical end-stop is reached', 'rejection_criteria' => 'Absent, mis-set, or bypassed limit device', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 9, 'clause_ref' => '8.1*', 'item' => 'SWL marking and duty/classification plate', 'question' => 'SWL marking and duty/classification plate', 'acceptance_criteria' => 'Legible, matches equipment and current examination record', 'rejection_criteria' => 'Illegible or missing capacity marking', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 10, 'clause_ref' => '9.1*', 'item' => 'Function test under no load', 'question' => 'Function test under no load', 'acceptance_criteria' => 'Smooth raise/lower operation through full travel, brake holds when released mid-travel', 'rejection_criteria' => 'Erratic operation, brake fails to hold when control released', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 11, 'clause_ref' => '9.2*', 'item' => 'Proof / dynamic load test (post-repair or per scheme)', 'question' => 'Proof / dynamic load test', 'acceptance_criteria' => 'No permanent deformation or chain/rope damage after test load removed', 'rejection_criteria' => 'Permanent deformation, rope/chain damage, or structural distress under test', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 12, 'clause_ref' => '10.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => "Manufacturer's declaration of conformity and prior thorough examination records available", 'rejection_criteria' => 'Missing or incomplete documentation', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        // 3. Hydraulic Jack Checklist (9 items)
        $jackChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Base and ram / piston assembly', 'question' => 'Base and ram / piston assembly', 'acceptance_criteria' => 'No cracks, bending, or excessive corrosion; ram surface free of scoring', 'rejection_criteria' => 'Cracked/bent base or ram, corrosion affecting structural integrity, scored ram surface', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Hydraulic seals and cylinder', 'question' => 'Hydraulic seals and cylinder', 'acceptance_criteria' => 'No visible leakage, seals intact, ram extends/retracts smoothly', 'rejection_criteria' => 'Hydraulic fluid leakage, seal failure, erratic ram movement', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'Release valve / pump mechanism', 'question' => 'Release valve / pump mechanism', 'acceptance_criteria' => 'Operates smoothly, controlled lowering, no sudden drop', 'rejection_criteria' => 'Uncontrolled or sudden lowering, seized/faulty release valve', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Saddle / lifting point', 'question' => 'Saddle / lifting point', 'acceptance_criteria' => 'Secure, correctly seated, no cracks or excessive wear', 'rejection_criteria' => 'Cracked, loose, or excessively worn saddle', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '5.2*', 'item' => 'Overload / pressure relief device', 'question' => 'Overload / pressure relief device', 'acceptance_criteria' => 'Functions at set pressure corresponding to rated capacity', 'rejection_criteria' => 'Relief device inoperative or set incorrectly', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '6.1*', 'item' => 'SWL / rated capacity marking', 'question' => 'SWL / rated capacity marking', 'acceptance_criteria' => 'Legible and matches examination record', 'rejection_criteria' => 'Illegible or missing capacity marking', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 7, 'clause_ref' => '7.1*', 'item' => 'Function test under no load', 'question' => 'Function test under no load', 'acceptance_criteria' => 'Smooth full-stroke raise and controlled lower', 'rejection_criteria' => 'Erratic stroke, sticking, or uncontrolled lowering', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 8, 'clause_ref' => '7.2*', 'item' => 'Load test to rated capacity (per scheme of examination)', 'question' => 'Load test to rated capacity', 'acceptance_criteria' => 'Holds rated load without drift for the specified hold period', 'rejection_criteria' => 'Load drift/creep exceeding tolerance during hold period', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 9, 'clause_ref' => '8.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => "Manufacturer's certificate and prior examination records available", 'rejection_criteria' => 'Missing or incomplete documentation', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        // 4. Forklift & Reach Truck Checklist (13 items)
        $forkliftChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Chassis, mast, and fork carriage / boom structure', 'question' => 'Chassis, mast, and fork carriage / boom structure', 'acceptance_criteria' => 'No cracks, distortion, or corrosion affecting structural integrity', 'rejection_criteria' => 'Cracks, bending, or corrosion compromising structural integrity', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Forks / attachment', 'question' => 'Forks / attachment', 'acceptance_criteria' => 'No cracks, wear at heel beyond discard limit, tines parallel and equal length', 'rejection_criteria' => 'Cracked forks, heel wear beyond discard limit, unequal fork length/angle', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'Mast rollers, chains, and hydraulic lift cylinder', 'question' => 'Mast rollers, chains, and hydraulic lift cylinder', 'acceptance_criteria' => 'No excessive wear, chain tension equal and within tolerance, no hydraulic leakage', 'rejection_criteria' => 'Worn rollers, unequal/slack chains, hydraulic leakage or erratic lift', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Tyres / wheels', 'question' => 'Tyres / wheels', 'acceptance_criteria' => "Tread and sidewalls free of cuts/damage exceeding manufacturer's limit, correctly inflated (pneumatic)", 'rejection_criteria' => "Cuts, chunking, or wear exceeding manufacturer's limit; incorrect inflation", 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '5.2*', 'item' => 'Braking system (service and parking brake)', 'question' => 'Braking system (service and parking brake)', 'acceptance_criteria' => 'Holds vehicle with rated load on maximum rated gradient', 'rejection_criteria' => 'Brake fails to hold rated load/gradient, excessive pedal travel', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '6.1*', 'item' => 'Steering system', 'question' => 'Steering system', 'acceptance_criteria' => 'Responsive, no excessive free play', 'rejection_criteria' => 'Excessive free play, binding, or loss of steering response', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 7, 'clause_ref' => '6.2*', 'item' => 'Overhead guard / operator restraint', 'question' => 'Overhead guard / operator restraint', 'acceptance_criteria' => 'Guard undamaged and secure; seatbelt/restraint fitted and functional', 'rejection_criteria' => 'Damaged/missing guard, non-functional or missing operator restraint', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 8, 'clause_ref' => '6.3*', 'item' => 'Warning devices (horn, reversing alarm, beacon)', 'question' => 'Warning devices (horn, reversing alarm, beacon)', 'acceptance_criteria' => 'All warning devices functional', 'rejection_criteria' => 'Any warning device inoperative', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 9, 'clause_ref' => '7.1*', 'item' => 'Load / capacity plate', 'question' => 'Load / capacity plate', 'acceptance_criteria' => 'Legible and matches current attachment and mast configuration', 'rejection_criteria' => 'Illegible, missing, or mismatched capacity plate', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 10, 'clause_ref' => '7.2*', 'item' => 'Stability / tilt function', 'question' => 'Stability / tilt function', 'acceptance_criteria' => 'Mast tilt operates smoothly forward and back within design limits', 'rejection_criteria' => 'Erratic or uncontrolled tilt operation', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 11, 'clause_ref' => '8.1*', 'item' => 'Function test under no load', 'question' => 'Function test under no load', 'acceptance_criteria' => 'All travel, lift, tilt, and steering functions operate smoothly', 'rejection_criteria' => 'Erratic operation of any function, unusual noise/vibration', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 12, 'clause_ref' => '8.2*', 'item' => 'Load test to rated capacity (per scheme of examination)', 'question' => 'Load test to rated capacity', 'acceptance_criteria' => 'Lifts and lowers rated load through full mast travel without instability or hydraulic drift', 'rejection_criteria' => 'Instability, hydraulic drift, or structural distress under rated load', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 13, 'clause_ref' => '9.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => "Manufacturer's declaration of conformity and prior examination/maintenance records available", 'rejection_criteria' => 'Missing or incomplete documentation', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        // 5. MEWP & Suspended Platform Checklist (13 items)
        $mewpChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Chassis / base structure and outriggers', 'question' => 'Chassis / base structure and outriggers', 'acceptance_criteria' => 'No cracks, distortion, or corrosion; outriggers fully extend and lock', 'rejection_criteria' => 'Cracks, deformation, corrosion; outrigger fails to extend/lock', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Scissor/boom structure and pivot pins', 'question' => 'Scissor/boom structure and pivot pins', 'acceptance_criteria' => 'No cracks, excessive wear at pins/bushes, welds sound', 'rejection_criteria' => 'Cracked structure, excessive pin/bush wear, cracked welds', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'Platform / basket guard-rails and gate', 'question' => 'Platform / basket guard-rails and gate', 'acceptance_criteria' => 'Rails intact, self-closing gate functions and latches', 'rejection_criteria' => 'Damaged/missing rails, gate fails to self-close or latch', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Hydraulic system (cylinders, hoses, valves)', 'question' => 'Hydraulic system (cylinders, hoses, valves)', 'acceptance_criteria' => 'No leakage, hoses undamaged, smooth controlled movement', 'rejection_criteria' => 'Leakage, damaged/chafed hoses, erratic or uncontrolled movement', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '5.2*', 'item' => 'Descent/overload safety valve or velocity fuse', 'question' => 'Descent/overload safety valve or velocity fuse', 'acceptance_criteria' => 'Functions correctly, prevents uncontrolled descent on hose failure', 'rejection_criteria' => 'Fails to arrest descent on simulated hose-failure test', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '6.1*', 'item' => 'Platform / ground controls', 'question' => 'Platform / ground controls', 'acceptance_criteria' => 'All functions correctly labelled and operate as intended; emergency stop functional', 'rejection_criteria' => 'Mislabelled or malfunctioning controls; non-functional emergency stop', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 7, 'clause_ref' => '6.2*', 'item' => 'Tilt / level sensor and cut-out', 'question' => 'Tilt / level sensor and cut-out', 'acceptance_criteria' => "Alarm and motion cut-out activate at manufacturer's rated tilt angle", 'rejection_criteria' => 'Tilt alarm/cut-out fails to activate at rated angle or is bypassed', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 8, 'clause_ref' => '6.3*', 'item' => 'Emergency lowering / auxiliary power system', 'question' => 'Emergency lowering / auxiliary power system', 'acceptance_criteria' => 'Functions correctly to lower platform in event of primary power loss', 'rejection_criteria' => 'Emergency lowering system inoperative', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 9, 'clause_ref' => '7.1*', 'item' => 'Anchorage points / harness attachment (boom types)', 'question' => 'Anchorage points / harness attachment (boom types)', 'acceptance_criteria' => 'Anchorage points present, undamaged, and correctly rated', 'rejection_criteria' => 'Missing, damaged, or unrated anchorage points', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 10, 'clause_ref' => '7.2*', 'item' => 'Rated capacity / occupancy marking', 'question' => 'Rated capacity / occupancy marking', 'acceptance_criteria' => 'Legible and matches current configuration', 'rejection_criteria' => 'Illegible, missing, or mismatched capacity marking', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 11, 'clause_ref' => '8.1*', 'item' => 'Function test under no load', 'question' => 'Function test under no load', 'acceptance_criteria' => 'Smooth full-range operation of all elevating, extending, and slewing functions', 'rejection_criteria' => 'Erratic operation, unusual noise, or failure of any function', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 12, 'clause_ref' => '8.2*', 'item' => 'Load/stability test to rated capacity (per scheme of examination)', 'question' => 'Load/stability test to rated capacity', 'acceptance_criteria' => 'No instability, structural distress, or hydraulic drift at full height/reach with rated load', 'rejection_criteria' => 'Instability, structural distress, or drift under rated load', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 13, 'clause_ref' => '9.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => "Manufacturer's declaration of conformity and prior examination records available", 'rejection_criteria' => 'Missing or incomplete documentation', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        // 6. Structural & Storage Checklist (9 items)
        $structuralChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Upright frames / columns', 'question' => 'Upright frames / columns', 'acceptance_criteria' => "No visible bending, twisting, or damage exceeding manufacturer's / SEMA damage-tolerance limit", 'rejection_criteria' => 'Bending, twisting, or impact damage exceeding tolerance limit (colour-coded risk category)', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Beams / horizontal members and connectors', 'question' => 'Beams / horizontal members and connectors', 'acceptance_criteria' => 'No bending, cracked welds, or dislodged connector locks', 'rejection_criteria' => 'Bent beams, cracked welds, dislodged or missing connector safety clips', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'Base plates and floor fixings', 'question' => 'Base plates and floor fixings', 'acceptance_criteria' => 'Securely anchored, no lifting or cracking of floor fixings', 'rejection_criteria' => 'Loose, lifted, or cracked base plate fixings', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Bracing and frame rigidity', 'question' => 'Bracing and frame rigidity', 'acceptance_criteria' => 'All diagonal/horizontal bracing intact and secure', 'rejection_criteria' => 'Missing, bent, or disconnected bracing', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '5.2*', 'item' => 'Load notices / rack configuration signage', 'question' => 'Load notices / rack configuration signage', 'acceptance_criteria' => 'Current, legible, and matches as-installed beam levels and bay loads', 'rejection_criteria' => 'Missing, illegible, or outdated load signage', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '6.1*', 'item' => 'Beam safety locks / pallet stops', 'question' => 'Beam safety locks / pallet stops', 'acceptance_criteria' => 'All safety locking pins/clips present and engaged', 'rejection_criteria' => 'Missing or disengaged locking pins/clips', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 7, 'clause_ref' => '6.2*', 'item' => 'Guarding (end-of-aisle, column protectors)', 'question' => 'Guarding (end-of-aisle, column protectors)', 'acceptance_criteria' => 'Fitted where required and undamaged', 'rejection_criteria' => 'Missing or damaged impact protection where risk assessment requires it', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 8, 'clause_ref' => '7.1*', 'item' => 'Overall verticality and alignment', 'question' => 'Overall verticality and alignment', 'acceptance_criteria' => "Within manufacturer's / SEMA tolerance for out-of-plumb", 'rejection_criteria' => 'Out-of-plumb exceeding tolerance', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 9, 'clause_ref' => '8.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => 'Rack layout drawing, load capacity data, and prior periodic inspection records available', 'rejection_criteria' => 'Missing or incomplete documentation', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        // 7. Synthetic Slings Checklist (8 items)
        $syntheticSlingChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Webbing body (full length, both faces)', 'question' => 'Webbing body (full length, both faces)', 'acceptance_criteria' => 'No cuts, abrasion, chemical/heat damage, or fibre discolouration beyond discard criteria', 'rejection_criteria' => 'Cuts, tears, holes, excessive abrasion, heat/chemical damage, or discolouration indicating fibre degradation', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Load-bearing eyes / soft eyes and reinforcement', 'question' => 'Load-bearing eyes / soft eyes and reinforcement', 'acceptance_criteria' => 'Reinforcement intact, no exposed load-bearing fibres', 'rejection_criteria' => 'Damaged reinforcement, exposed or cut load-bearing yarns at eye', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'Stitching', 'question' => 'Stitching', 'acceptance_criteria' => 'All stitch patterns intact, no broken or pulled threads', 'rejection_criteria' => 'Broken, pulled, or worn stitching affecting load path', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Fittings (where fitted: rings, hooks)', 'question' => 'Fittings (where fitted: rings, hooks)', 'acceptance_criteria' => 'No cracks, deformation, or excessive wear at bearing point', 'rejection_criteria' => 'Cracked, deformed, or worn fittings', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '6.1*', 'item' => 'Identification / WLL label', 'question' => 'Identification / WLL label', 'acceptance_criteria' => 'Legible, matches sling type, mode of use, and rated capacity', 'rejection_criteria' => 'Illegible, missing, or mismatched identification label', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '6.2*', 'item' => 'Angle-of-use markings (multi-leg slings)', 'question' => 'Angle-of-use markings (multi-leg slings)', 'acceptance_criteria' => 'Legible and consistent with rated capacity table', 'rejection_criteria' => 'Illegible or missing angle/capacity markings', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 7, 'clause_ref' => '7.1*', 'item' => 'Overall flexibility and handling condition', 'question' => 'Overall flexibility and handling condition', 'acceptance_criteria' => 'Sling remains pliable, no stiffening from contamination', 'rejection_criteria' => 'Stiffening, embedded grit, or contamination affecting flexibility', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 8, 'clause_ref' => '8.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => 'Certificate of conformity/test certificate and prior examination record available', 'rejection_criteria' => 'Missing or incomplete documentation', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        // 8. Metallic Slings Checklist (7 items)
        $metallicSlingChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Chain links / wire rope body (full length)', 'question' => 'Chain links / wire rope body (full length)', 'acceptance_criteria' => 'No cracks, gouges, corrosion pitting, or wear exceeding discard diameter/thickness tolerance', 'rejection_criteria' => 'Cracks, gouges, nicks, corrosion, or wear exceeding discard tolerance', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Elongation / stretch (chain slings)', 'question' => 'Elongation / stretch (chain slings)', 'acceptance_criteria' => "Within manufacturer's permissible elongation limit versus original reference length", 'rejection_criteria' => 'Elongation exceeding permissible limit indicating overload history', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'Broken wires / birdcaging / kinking (wire rope slings)', 'question' => 'Broken wires / birdcaging / kinking (wire rope slings)', 'acceptance_criteria' => 'No broken wires beyond discard criteria, no kinks or core protrusion', 'rejection_criteria' => 'Broken wires beyond discard limit, kinking, birdcaging, or core protrusion', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Master link / end fittings (hooks, thimbles, ferrules)', 'question' => 'Master link / end fittings (hooks, thimbles, ferrules)', 'acceptance_criteria' => 'No cracks, deformation, or excessive wear; ferrules/swages correctly formed', 'rejection_criteria' => 'Cracked/deformed fittings, incorrectly formed or slipping ferrule/swage', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '5.2*', 'item' => 'Hook safety latch (where fitted)', 'question' => 'Hook safety latch (where fitted)', 'acceptance_criteria' => 'Present and functional, no throat opening beyond tolerance', 'rejection_criteria' => 'Missing/inoperative latch, throat opening beyond tolerance', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '6.1*', 'item' => 'Identification / WLL tag', 'question' => 'Identification / WLL tag', 'acceptance_criteria' => 'Legible, matches sling configuration and rated capacity', 'rejection_criteria' => 'Illegible, missing, or mismatched WLL tag', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 7, 'clause_ref' => '7.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => 'Certificate of conformity/test certificate and prior examination record available', 'rejection_criteria' => 'Missing or incomplete documentation', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        // 9. Loose Lifting Accessories (Shackles, Hooks, Eyebolts - 6 items)
        $looseGearChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Body (bow, pin, or hook shank)', 'question' => 'Body (bow, pin, or hook shank)', 'acceptance_criteria' => 'No cracks, gouges, or excessive corrosion pitting', 'rejection_criteria' => 'Cracks, gouges, or corrosion pitting affecting cross-section', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Wear at load-bearing contact points', 'question' => 'Wear at load-bearing contact points', 'acceptance_criteria' => "Wear within manufacturer's/standard's permissible reduction of original dimension", 'rejection_criteria' => 'Wear exceeding permissible dimensional reduction', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'Deformation / thread condition (shackle pins, eyebolt thread)', 'question' => 'Deformation / thread condition (shackle pins, eyebolt thread)', 'acceptance_criteria' => 'No bending, thread damage, or elongation; pin/collar seats correctly', 'rejection_criteria' => 'Bent pin, damaged/stripped thread, elongation, or improper seating', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Safety latch (hooks only)', 'question' => 'Safety latch (hooks only)', 'acceptance_criteria' => 'Present, undamaged, and closes fully across throat opening', 'rejection_criteria' => 'Missing, damaged, or fails to close fully', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '6.1*', 'item' => 'WLL / rated capacity marking', 'question' => 'WLL / rated capacity marking', 'acceptance_criteria' => 'Forged/stamped marking legible and matches item and use configuration', 'rejection_criteria' => 'Illegible, missing, or mismatched capacity marking', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '7.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => "Manufacturer's certificate of conformity and prior examination record available", 'rejection_criteria' => 'Missing or incomplete documentation', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        // 10. Below-the-hook & Lifting Beams / Clamps / Magnets (9 items)
        $belowTheHookChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Main structural frame / beam', 'question' => 'Main structural frame / beam', 'acceptance_criteria' => 'No cracks, bending, or corrosion affecting structural integrity', 'rejection_criteria' => 'Cracks, bending, or corrosion affecting integrity', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Lifting/attachment points (eyes, trunnions, master link)', 'question' => 'Lifting/attachment points (eyes, trunnions, master link)', 'acceptance_criteria' => 'No cracks, deformation, or excessive wear', 'rejection_criteria' => 'Cracked, deformed, or excessively worn attachment points', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'Clamping jaws / grip mechanism (clamps) or magnet/vacuum pad face', 'question' => 'Clamping jaws / grip mechanism or magnet/vacuum pad face', 'acceptance_criteria' => 'Jaws grip evenly with correct force; magnet face / vacuum pads intact and produce full contact', 'rejection_criteria' => 'Uneven or slipping grip; damaged magnet face or vacuum pad, loss of seal/contact', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Locking / safety devices', 'question' => 'Locking / safety devices', 'acceptance_criteria' => 'All locking pins, safety catches, or fail-safe mechanisms functional', 'rejection_criteria' => 'Locking or fail-safe device inoperative', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '5.2*', 'item' => 'Power supply / battery and control unit (magnets & vacuum lifters)', 'question' => 'Power supply / battery and control unit', 'acceptance_criteria' => 'Charge level adequate, indicator lights functional, backup/fail-safe alarm operative', 'rejection_criteria' => 'Inadequate charge, non-functional indicator or backup alarm', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '6.1*', 'item' => 'SWL / rated capacity marking', 'question' => 'SWL / rated capacity marking', 'acceptance_criteria' => 'Legible and matches configuration (jaw opening range / material type as applicable)', 'rejection_criteria' => 'Illegible, missing, or mismatched capacity marking', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 7, 'clause_ref' => '7.1*', 'item' => 'Function test under no load', 'question' => 'Function test under no load', 'acceptance_criteria' => 'Correct engagement/release cycle, all safety interlocks operate', 'rejection_criteria' => 'Failure of engagement/release cycle or safety interlock', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 8, 'clause_ref' => '7.2*', 'item' => 'Load test to rated capacity (per scheme of examination)', 'question' => 'Load test to rated capacity', 'acceptance_criteria' => 'Secure hold of rated load through test cycle with no slip', 'rejection_criteria' => 'Slippage, loss of grip/contact, or structural distress under rated load', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 9, 'clause_ref' => '8.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => "Manufacturer's declaration of conformity and prior examination records available", 'rejection_criteria' => 'Missing or incomplete documentation', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        // 11. Load Securing Equipment (6 items)
        $loadSecuringChecklist = [
            ['no' => 1, 'clause_ref' => '4.1*', 'item' => 'Webbing strap / chain / wire rope body', 'question' => 'Webbing strap / chain / wire rope body', 'acceptance_criteria' => 'No cuts, abrasion, corrosion, or heat/chemical damage beyond discard criteria', 'rejection_criteria' => 'Cuts, tears, corrosion, or heat/chemical damage beyond discard criteria', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 2, 'clause_ref' => '4.2*', 'item' => 'Ratchet / tensioning mechanism', 'question' => 'Ratchet / tensioning mechanism', 'acceptance_criteria' => 'Operates smoothly, pawl engages fully, no slipping under tension', 'rejection_criteria' => 'Seized, damaged, or slipping ratchet mechanism', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 3, 'clause_ref' => '4.3*', 'item' => 'End fittings (hooks, claws, J-hooks)', 'question' => 'End fittings (hooks, claws, J-hooks)', 'acceptance_criteria' => 'No cracks, deformation, or excessive wear; safety catch (if fitted) functional', 'rejection_criteria' => 'Cracked, deformed, or worn end fittings; inoperative safety catch', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 4, 'clause_ref' => '5.1*', 'item' => 'Lashing capacity (LC) / STF label', 'question' => 'Lashing capacity (LC) / STF label', 'acceptance_criteria' => 'Legible and matches strap/chain type and configuration', 'rejection_criteria' => 'Illegible, missing, or mismatched LC/STF label', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 5, 'clause_ref' => '6.1*', 'item' => 'Edge protectors / corner protection (where used)', 'question' => 'Edge protectors / corner protection (where used)', 'acceptance_criteria' => 'Undamaged and correctly positioned at load edges', 'rejection_criteria' => 'Damaged or missing edge protection where required by load securing plan', 'status' => 'Pass', 'remarks' => 'In Order'],
            ['no' => 6, 'clause_ref' => '7.1*', 'item' => 'Documentation review', 'question' => 'Documentation review', 'acceptance_criteria' => "Manufacturer's certificate and prior inspection record available", 'rejection_criteria' => 'Missing or incomplete documentation', 'status' => 'Pass', 'remarks' => 'In Order'],
        ];

        return [
            // 1
            [
                'code' => 'SAF-INS-01',
                'name' => 'Overhead Travelling Crane (رافعة علوية متحركة)',
                'equipment_type' => 'crane',
                'equipment_description' => 'Overhead Travelling Crane (Bridge Crane / EOT Crane)',
                'reference_standard' => 'BS 7121-1, BS EN 15011, LOLER 1998',
                'validity_months' => 12,
                'swl' => 'As Marked / Nameplate',
                'tests_carried_out' => 'Visual Examination, Functional & Limit Switch Testing, Load Test (as applicable)',
                'checklist_items' => $craneChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 12 months (LOLER 1998 Sch.1 - other lifting equipment)',
            ],
            // 2
            [
                'code' => 'SAF-INS-02',
                'name' => 'Mobile Crane (ونش متنقل)',
                'equipment_type' => 'crane',
                'equipment_description' => 'Mobile Telescopic / All-Terrain Crane',
                'reference_standard' => 'BS 7121 Series',
                'validity_months' => 12,
                'swl' => 'Per Load Chart',
                'tests_carried_out' => 'Visual Inspection, LMI & Outrigger Testing, Operational & Load Test',
                'checklist_items' => $craneChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 12 months (LOLER 1998 Sch.1 - other lifting equipment)',
            ],
            // 3
            [
                'code' => 'SAF-INS-03',
                'name' => 'Tower Crane (ونش برجي)',
                'equipment_type' => 'crane',
                'equipment_description' => 'Tower Crane (Top-Slewing / Self-Erecting)',
                'reference_standard' => 'BS 7121 Series',
                'validity_months' => 12,
                'swl' => 'Per Radius Load Chart',
                'tests_carried_out' => 'Structural, Mast, Jib, Wire Rope and Overload Limit System Examination',
                'checklist_items' => $craneChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 12 months (LOLER 1998 Sch.1 - other lifting equipment)',
            ],
            // 4
            [
                'code' => 'SAF-INS-04',
                'name' => 'Jib Crane (ونش ذراعي)',
                'equipment_type' => 'crane',
                'equipment_description' => 'Pillar / Wall-Mounted Slewing Jib Crane',
                'reference_standard' => 'BS EN 14985',
                'validity_months' => 12,
                'swl' => 'As Marked',
                'tests_carried_out' => 'Visual Inspection, Slew Mechanism & Deflection Verification',
                'checklist_items' => $craneChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 12 months (LOLER 1998 Sch.1 - other lifting equipment)',
            ],
            // 5
            [
                'code' => 'SAF-INS-05',
                'name' => 'Chain Hoist (رافعة جنزير يدوية)',
                'equipment_type' => 'hoist',
                'equipment_description' => 'Manual Chain Hoist / Chain Block',
                'reference_standard' => 'BS EN 13157',
                'validity_months' => 6,
                'swl' => 'Rated Capacity',
                'tests_carried_out' => 'Thorough Examination, Hook Throat Opening, Load Chain Wear & Brake Hold',
                'checklist_items' => $hoistChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 6
            [
                'code' => 'SAF-INS-06',
                'name' => 'Electric Hoist (رافعة كهربائية)',
                'equipment_type' => 'hoist',
                'equipment_description' => 'Electric Wire Rope / Chain Hoist',
                'reference_standard' => 'BS EN 14492-2',
                'validity_months' => 6,
                'swl' => 'Rated Capacity',
                'tests_carried_out' => 'Electrical Controls, Limit Switches, Wire Rope & Brake Test Under Load',
                'checklist_items' => $hoistChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 7
            [
                'code' => 'SAF-INS-07',
                'name' => 'Lever Hoist (رافعة رافعة يدوية / بتشيت)',
                'equipment_type' => 'hoist',
                'equipment_description' => 'Manual Lever Hoist / Come-Along Ratchet Puller',
                'reference_standard' => 'BS EN 13157',
                'validity_months' => 6,
                'swl' => 'Rated Capacity',
                'tests_carried_out' => 'Pawl & Ratchet Engagement, Hook Deformation, Chain Integrity',
                'checklist_items' => $hoistChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 8
            [
                'code' => 'SAF-INS-08',
                'name' => 'Winch (ونش سحب ورفع)',
                'equipment_type' => 'winch',
                'equipment_description' => 'Power Driven Winch / Capstan',
                'reference_standard' => 'BS EN 14492',
                'validity_months' => 12,
                'swl' => 'Line Pull / Rated Capacity',
                'tests_carried_out' => 'Drum Spooling, Brake Holding Test, Wire Rope Inspection & Mount Anchorage',
                'checklist_items' => $hoistChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 12 months (LOLER 1998 Sch.1 - other lifting equipment)',
            ],
            // 9
            [
                'code' => 'SAF-INS-09',
                'name' => 'Hydraulic Jack (كوريك هيدروليكي)',
                'equipment_type' => 'jack',
                'equipment_description' => 'Hydraulic Bottle / Toe / Floor Jack',
                'reference_standard' => 'BS EN 1494',
                'validity_months' => 12,
                'swl' => 'Rated Capacity',
                'tests_carried_out' => 'Full-Stroke Extension, Pressure Hold & Relief Valve Verification',
                'checklist_items' => $jackChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 12 months (LOLER 1998 Sch.1 - other lifting equipment)',
            ],
            // 10
            [
                'code' => 'SAF-INS-10',
                'name' => 'Forklift Truck (رافعة شوكية)',
                'equipment_type' => 'forklift',
                'equipment_description' => 'Counterbalance Forklift Truck (Diesel / Electric / LPG)',
                'reference_standard' => 'BS EN ISO 3691-1',
                'validity_months' => 12,
                'swl' => 'Rated Capacity @ Load Center',
                'tests_carried_out' => 'Mast, Forks Heel Thickness, Lift Chains, Brakes, Steering & Safety Restraints',
                'checklist_items' => $forkliftChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 12 months (LOLER 1998 Sch.1 - other lifting equipment)',
            ],
            // 11
            [
                'code' => 'SAF-INS-11',
                'name' => 'Reach Truck (رافعة المستودعات ريتش تراك)',
                'equipment_type' => 'forklift',
                'equipment_description' => 'Narrow Aisle Reach Truck',
                'reference_standard' => 'BS EN ISO 3691-1',
                'validity_months' => 12,
                'swl' => 'Rated Capacity',
                'tests_carried_out' => 'Reach Carriage, Mast Tilt, Steering Interlocks, Load Drift Verification',
                'checklist_items' => $forkliftChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 12 months (LOLER 1998 Sch.1 - other lifting equipment)',
            ],
            // 12
            [
                'code' => 'SAF-INS-12',
                'name' => 'MEWP - Scissor Lift (منصة مقصية متنقلة)',
                'equipment_type' => 'mewp',
                'equipment_description' => 'Mobile Elevating Work Platform (Scissor Lift)',
                'reference_standard' => 'BS EN 280-1',
                'validity_months' => 6,
                'swl' => 'Platform Capacity / Persons Allowed',
                'tests_carried_out' => 'Emergency Lowering, Tilt Cut-out, Overload Sensor & Scissor Bushings',
                'checklist_items' => $mewpChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 13
            [
                'code' => 'SAF-INS-13',
                'name' => 'MEWP - Boom Lift (مان ليفت ذراعي)',
                'equipment_type' => 'mewp',
                'equipment_description' => 'Articulated / Telescopic Boom Lift (Cherry Picker)',
                'reference_standard' => 'BS EN 280-1',
                'validity_months' => 6,
                'swl' => 'Basket Capacity',
                'tests_carried_out' => 'Boom Telescoping, Slewing Interlocks, Level Sensors, Harness Anchorages',
                'checklist_items' => $mewpChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 14
            [
                'code' => 'SAF-INS-14',
                'name' => 'Suspended Platform - Gondola (سقالة معلقة / جوندولا)',
                'equipment_type' => 'mewp',
                'equipment_description' => 'Temporary / Permanent Suspended Access Platform',
                'reference_standard' => 'BS EN 1808',
                'validity_months' => 6,
                'swl' => 'Rated Basket Capacity',
                'tests_carried_out' => 'Traction Hoists, Fall Arrest Safety Devices, Wire Ropes & Suspension Rigging',
                'checklist_items' => $mewpChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 15
            [
                'code' => 'SAF-INS-15',
                'name' => 'Steel Storage Racking (أرفف التخزين المعدنية الصناعية)',
                'equipment_type' => 'storage',
                'equipment_description' => 'Adjustable Pallet Racking (SEMA-Aligned)',
                'reference_standard' => 'BS EN 15635',
                'validity_months' => 12,
                'swl' => 'Safe Bay / Shelf Load Notice',
                'tests_carried_out' => 'Upright Plumb, Beam Deflection, Safety Pins, Anchor Bolts & Impact Damage Classification',
                'checklist_items' => $structuralChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => "Not LOLER-scoped; SEMA-aligned expert structural inspection - typically annual (verify against Safero's risk-based scheme)",
            ],
            // 16
            [
                'code' => 'SAF-INS-16',
                'name' => 'Offshore Container (حاوية بحرية أوفشور)',
                'equipment_type' => 'container',
                'equipment_description' => 'Offshore Freight Container / Skid Unit & Lifting Sling Set',
                'reference_standard' => 'BS EN ISO 10855-1',
                'validity_months' => 12,
                'swl' => 'Max Gross Weight (Rating) / Tare / Payload',
                'tests_carried_out' => 'Pad Eyes NDT, Structural Frame, Corner Castings & Slings Examination',
                'checklist_items' => $structuralChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 12 months (LOLER 1998 Sch.1 - other lifting equipment)',
            ],
            // 17
            [
                'code' => 'SAF-INS-17',
                'name' => 'Personnel Basket (باسكت رفع أفراد)',
                'equipment_type' => 'basket',
                'equipment_description' => 'Crane Suspended Personnel Basket / Man Basket',
                'reference_standard' => 'BS EN 14502-1',
                'validity_months' => 6,
                'swl' => 'Rated Number of Persons + Equipment',
                'tests_carried_out' => 'Mesh Enclosure, Fall Arrest Anchors, Slings, Proof Load Test & Free Drop Device',
                'checklist_items' => $structuralChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 18
            [
                'code' => 'SAF-INS-18',
                'name' => 'Webbing Sling (واير قماش مسطح)',
                'equipment_type' => 'sling',
                'equipment_description' => 'Flat Woven Webbing Sling (Synthetic Polyester)',
                'reference_standard' => 'BS EN 1492-1',
                'validity_months' => 6,
                'swl' => 'WLL per Mode of Use',
                'tests_carried_out' => 'Visual Examination, Stitching Integrity, Cuts, Chemical & Abrasion Check',
                'checklist_items' => $syntheticSlingChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 19
            [
                'code' => 'SAF-INS-19',
                'name' => 'Round Sling (واير قماش دائري)',
                'equipment_type' => 'sling',
                'equipment_description' => 'Endless Round Polyester Sling',
                'reference_standard' => 'BS EN 1492-2',
                'validity_months' => 6,
                'swl' => 'WLL Straight / Choke / Basket',
                'tests_carried_out' => 'Protective Sleeve Examination, Internal Load Core Fiber Continuity',
                'checklist_items' => $syntheticSlingChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 20
            [
                'code' => 'SAF-INS-20',
                'name' => 'Wire Rope Sling (واير صلب / سلكي)',
                'equipment_type' => 'sling',
                'equipment_description' => 'Steel Wire Rope Sling (Single or Multi-Leg)',
                'reference_standard' => 'BS EN 13414',
                'validity_months' => 6,
                'swl' => 'WLL per Angle Chart',
                'tests_carried_out' => 'Broken Wires Count, Kinking, Birdcaging, Ferrules & Thimbles Integrity',
                'checklist_items' => $metallicSlingChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 21
            [
                'code' => 'SAF-INS-21',
                'name' => 'Chain Sling (واير جنزير صلب)',
                'equipment_type' => 'sling',
                'equipment_description' => 'Grade 80 / 100 Short Link Chain Sling (1-4 Legs)',
                'reference_standard' => 'BS EN 818 Series',
                'validity_months' => 6,
                'swl' => 'WLL per Leg Angle',
                'tests_carried_out' => 'Link Diameter Discard Limit, Elongation Measurement, Master Link & Hooks',
                'checklist_items' => $metallicSlingChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 22
            [
                'code' => 'SAF-INS-22',
                'name' => 'Shackles (شكالات رفع أمان)',
                'equipment_type' => 'accessory',
                'equipment_description' => 'Forged Steel Bow / Dee Shackles (Screw Pin or Safety Bolt)',
                'reference_standard' => 'BS EN 13889',
                'validity_months' => 6,
                'swl' => 'WLL Stamped on Body',
                'tests_carried_out' => 'Bow Distortion, Pin Thread Condition, NDT / MPI and Wear Limits',
                'checklist_items' => $looseGearChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 23
            [
                'code' => 'SAF-INS-23',
                'name' => 'Hooks (خطافات الرفع)',
                'equipment_type' => 'accessory',
                'equipment_description' => 'Drop Forged Steel Lifting Hooks (Eye, Clevis, Swivel)',
                'reference_standard' => 'BS EN 1677',
                'validity_months' => 6,
                'swl' => 'WLL Stamped',
                'tests_carried_out' => 'Safety Latch Operation, Throat Opening Tolerance & Magnetic Particle Inspection',
                'checklist_items' => $looseGearChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 24
            [
                'code' => 'SAF-INS-24',
                'name' => 'Eyebolts (مسامير عين للرفع)',
                'equipment_type' => 'accessory',
                'equipment_description' => 'Collar Eyebolts / Swivel Hoist Rings',
                'reference_standard' => 'BS EN ISO 3266, EN 1677',
                'validity_months' => 6,
                'swl' => 'WLL per Axial / Angular Pull',
                'tests_carried_out' => 'Thread Inspection, Shank Straightness, Collar Seating & MPI Examination',
                'checklist_items' => $looseGearChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 25
            [
                'code' => 'SAF-INS-25',
                'name' => 'Lifting Beams & Spreader Beams (كمرات الرفع وسبردر بيم)',
                'equipment_type' => 'beam',
                'equipment_description' => 'Custom Lifting Beam / Modular Spreader Beam',
                'reference_standard' => 'BS EN 13155',
                'validity_months' => 6,
                'swl' => 'Rated SWL at Working Spans',
                'tests_carried_out' => 'Main Girder Welds NDT, Lifting Lugs Distortion, Deflection Under Proof Load',
                'checklist_items' => $belowTheHookChecklist,
                'is_active' => true,
                'is_default' => true,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 26
            [
                'code' => 'SAF-INS-26',
                'name' => 'Lifting Clamps (كلابات وكلبسات رفع الألواح)',
                'equipment_type' => 'clamp',
                'equipment_description' => 'Plate Lifting Clamps (Vertical / Horizontal)',
                'reference_standard' => 'BS EN 13155',
                'validity_months' => 6,
                'swl' => 'WLL & Plate Thickness Range',
                'tests_carried_out' => 'Cam & Pad Teeth Wear, Locking Lever Spring Tension, Proof Load Hold',
                'checklist_items' => $belowTheHookChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 27
            [
                'code' => 'SAF-INS-27',
                'name' => 'Magnets & Vacuum Lifters (مغانط وروافع تفريغ الهواء)',
                'equipment_type' => 'magnet_vacuum',
                'equipment_description' => 'Permanent / Battery Electric Lifting Magnets & Vacuum Lifters',
                'reference_standard' => 'BS EN 13155',
                'validity_months' => 6,
                'swl' => 'Tear-off Capacity / SWL',
                'tests_carried_out' => 'Magnet Pole Face, Vacuum Reservoir, Low Pressure Warning Alarm & Breakaway Force',
                'checklist_items' => $belowTheHookChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Every 6 months (LOLER 1998 Sch.1 - lifting accessory / equipment for lifting persons)',
            ],
            // 28
            [
                'code' => 'SAF-INS-28',
                'name' => 'Load Securing Equipment (أحزمة وتجهيزات تأمين وتثبيت الحمولات)',
                'equipment_type' => 'lashing',
                'equipment_description' => 'Webbing Ratchet Lashings / Chain Load Binders',
                'reference_standard' => 'EN 12195-1',
                'validity_months' => 6,
                'swl' => 'Lashing Capacity (LC) / STF',
                'tests_carried_out' => 'Strap Weave, Tensioner Ratchet Locking, Hooks & Edge Protection Assessment',
                'checklist_items' => $loadSecuringChecklist,
                'is_active' => true,
                'is_default' => false,
                'notes' => 'Not LOLER-scoped; per Safero/operator load-securing inspection policy (recommended: before each use + periodic detailed inspection)',
            ],
        ];
    }
}
