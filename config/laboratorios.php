<?php

return [
    /* Valores tomados del formato autorizado de resultados de laboratorio. */
    'analisis' => [
        ['campo' => 'hto', 'nombre' => 'HEMATOCRITO', 'unidad' => '%', 'referencia' => 'VARONES: 42.0 - 54.0 / MUJERES: 37.0 - 48.0', 'area' => 'Hematología', 'orden' => 10],
        ['campo' => 'hb', 'nombre' => 'HEMOGLOBINA', 'unidad' => 'g/dL', 'referencia' => 'VARONES: 14.0 - 18.0 / MUJERES: 12.0 - 16.0', 'area' => 'Hematología', 'orden' => 20],
        ['campo' => 'upre', 'nombre' => 'UREA PRE', 'unidad' => 'mg/dL', 'referencia' => '10 - 50', 'area' => 'Bioquímica', 'orden' => 30],
        ['campo' => 'upost', 'nombre' => 'UREA POST', 'unidad' => 'mg/dL', 'referencia' => '10 - 50', 'area' => 'Bioquímica', 'orden' => 40],
        ['campo' => null, 'nombre' => 'Perfil de electrolitos (Cloro, Sodio y Potasio)', 'unidad' => '', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 50, 'encabezado' => true],
        ['campo' => 'cloro', 'nombre' => 'Cloro', 'unidad' => 'mmol/L', 'referencia' => '98 - 107', 'area' => 'Bioquímica', 'orden' => 60],
        ['campo' => 'sodio', 'nombre' => 'Sodio', 'unidad' => 'mmol/L', 'referencia' => '135 - 148', 'area' => 'Bioquímica', 'orden' => 70],
        ['campo' => 'potasio', 'nombre' => 'Potasio', 'unidad' => 'mmol/L', 'referencia' => '3.5 - 5.3', 'area' => 'Bioquímica', 'orden' => 80],
        ['campo' => 'fosforo_serico', 'nombre' => 'Dosaje de Fósforo inorgánico (fosfato)', 'unidad' => 'mg/dL', 'referencia' => '2.5 - 5.6', 'area' => 'Bioquímica', 'orden' => 90],
        ['campo' => 'calcio_serico', 'nombre' => 'Dosaje de Calcio total', 'unidad' => 'mg/dL', 'referencia' => '8.8 - 10.2', 'area' => 'Bioquímica', 'orden' => 100],
        ['campo' => 'tgo', 'nombre' => 'Aspartato aminotransferasa (AST/TGO)', 'unidad' => 'U/L', 'referencia' => 'Varones: menor a 50 - Mujeres: menor a 35', 'area' => 'Bioquímica', 'orden' => 110],
        ['campo' => 'tgp', 'nombre' => 'Alanina aminotransferasa (ALT/TGP)', 'unidad' => 'U/L', 'referencia' => 'Varones: menor a 50 - Mujeres: menor a 36', 'area' => 'Bioquímica', 'orden' => 120],
        ['campo' => 'albumina_serica', 'nombre' => 'Dosaje de Albúmina sérica', 'unidad' => 'g/dL', 'referencia' => '3.97 a 4.94', 'area' => 'Bioquímica', 'orden' => 130],
        ['campo' => 'fosfatasa', 'nombre' => 'Dosaje de Fosfatasa alcalina', 'unidad' => 'U/L', 'referencia' => 'Varones: menor a 50 - Mujeres: menor a 36', 'area' => 'Bioquímica', 'orden' => 140],
        ['campo' => 'hierro_serico', 'nombre' => 'Dosaje de Hierro sérico', 'unidad' => 'µg/dL', 'referencia' => '59 - 158', 'area' => 'Bioquímica', 'orden' => 150],
        ['campo' => 'ferritina', 'nombre' => 'Dosaje de Ferritina', 'unidad' => 'ng/mL', 'referencia' => '30 - 400', 'area' => 'Bioquímica', 'orden' => 160],
        ['campo' => 'transferrina', 'nombre' => 'Dosaje de Transferrina', 'unidad' => 'mg/dL', 'referencia' => '120 - 400', 'area' => 'Bioquímica', 'orden' => 170],
        ['campo' => 'pth', 'nombre' => 'Dosaje de Paratohormona (PTH)', 'unidad' => 'pg/mL', 'referencia' => 'Adultos: 10 - 65 / Niños: 9 - 52', 'area' => 'Bioquímica', 'orden' => 180],
        ['campo' => 'vih_1_2', 'nombre' => 'Anticuerpos VIH 1 y 2', 'unidad' => 'COI', 'referencia' => 'INF. 0.90 NO REACTIVO', 'area' => 'Bioquímica', 'orden' => 190],
        ['campo' => 'rpr', 'nombre' => 'Prueba de Sífilis – Anticuerpo No Treponémico (RPR), cualitativo', 'unidad' => 'COI', 'referencia' => 'NO REACTIVO', 'area' => 'Bioquímica', 'orden' => 200],
        ['campo' => 'hbsag', 'nombre' => 'Antígeno de superficie de Hepatitis B (HBsAg)', 'unidad' => 'COI', 'referencia' => 'Menor 0.90: negativo / Mayor 1: positivo / 0.90 - 0.99: indeterminado', 'area' => 'Bioquímica', 'orden' => 210],
        ['campo' => 'anti_hbs', 'nombre' => 'Anticuerpo contra el antígeno de superficie de Hepatitis B (Anti-HBs)', 'unidad' => 'mUI/mL', 'referencia' => 'INF. 10 positivo / SUP. 10 NEGATIVO', 'area' => 'Bioquímica', 'orden' => 220],
        ['campo' => 'anti_hbc_total', 'nombre' => 'Anticuerpo contra el antígeno de la nucleocápside de Hepatitis B (Anti-HBc total)', 'unidad' => 'COI', 'referencia' => 'INF. 1 positivo / SUP. 1 negativo', 'area' => 'Bioquímica', 'orden' => 230],
        ['campo' => 'hcv', 'nombre' => 'Anticuerpo contra Hepatitis C', 'unidad' => 'COI', 'referencia' => 'Menor 0.90: negativo / Mayor 1: positivo / 0.90 - 0.99: indeterminado', 'area' => 'Bioquímica', 'orden' => 240],
        ['campo' => 'htlv_1_2', 'nombre' => 'Anticuerpo para HTLV 1 y 2', 'unidad' => 'COI', 'referencia' => 'INF. 1: No reactivo / SUP. 1: Reactivo', 'area' => 'Bioquímica', 'orden' => 250],
    ],
];
