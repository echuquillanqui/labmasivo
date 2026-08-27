<?php

return [
    /* Las referencias vacías quedan centralizadas para completarlas desde el formato autorizado. */
    'analisis' => [
        ['campo' => 'hto', 'nombre' => 'HEMATOCRITO', 'unidad' => '%', 'referencia' => '', 'area' => 'Hematología', 'orden' => 10],
        ['campo' => 'hb', 'nombre' => 'HEMOGLOBINA', 'unidad' => 'g/dL', 'referencia' => '', 'area' => 'Hematología', 'orden' => 20],
        ['campo' => 'upre', 'nombre' => 'UREA PRE', 'unidad' => 'mg/dL', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 30],
        ['campo' => 'upost', 'nombre' => 'UREA POST', 'unidad' => 'mg/dL', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 40],
        ['campo' => null, 'nombre' => 'Perfil de electrolitos (Cloro, Sodio y Potasio)', 'unidad' => '', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 50, 'encabezado' => true],
        ['campo' => 'cloro', 'nombre' => 'Cloro', 'unidad' => 'mmol/L', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 60],
        ['campo' => 'sodio', 'nombre' => 'Sodio', 'unidad' => 'mmol/L', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 70],
        ['campo' => 'potasio', 'nombre' => 'Potasio', 'unidad' => 'mmol/L', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 80],
        ['campo' => 'fosforo_serico', 'nombre' => 'Dosaje de Fósforo inorgánico (fosfato)', 'unidad' => 'mg/dL', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 90],
        ['campo' => 'calcio_serico', 'nombre' => 'Dosaje de Calcio total', 'unidad' => 'mg/dL', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 100],
        ['campo' => 'tgo', 'nombre' => 'Aspartato aminotransferasa (AST/TGO)', 'unidad' => 'U/L', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 110],
        ['campo' => 'tgp', 'nombre' => 'Alanina aminotransferasa (ALT/TGP)', 'unidad' => 'U/L', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 120],
        ['campo' => 'albumina_serica', 'nombre' => 'Dosaje de Albúmina sérica', 'unidad' => 'g/dL', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 130],
        ['campo' => 'fosfatasa', 'nombre' => 'Dosaje de Fosfatasa alcalina', 'unidad' => 'U/L', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 140],
        ['campo' => 'hierro_serico', 'nombre' => 'Dosaje de Hierro sérico', 'unidad' => 'µg/dL', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 150],
        ['campo' => 'ferritina', 'nombre' => 'Dosaje de Ferritina', 'unidad' => 'ng/mL', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 160],
        ['campo' => 'transferrina', 'nombre' => 'Dosaje de Transferrina', 'unidad' => 'mg/dL', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 170],
        ['campo' => 'pth', 'nombre' => 'Dosaje de Paratohormona (PTH)', 'unidad' => 'pg/mL', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 180],
        ['campo' => 'vih_1_2', 'nombre' => 'Anticuerpos VIH 1 y 2', 'unidad' => 'COI', 'referencia' => 'NO REACTIVO', 'area' => 'Bioquímica', 'orden' => 190],
        ['campo' => 'rpr', 'nombre' => 'Prueba de Sífilis – Anticuerpo No Treponémico (RPR), cualitativo', 'unidad' => 'COI', 'referencia' => 'NO REACTIVO', 'area' => 'Bioquímica', 'orden' => 200],
        ['campo' => 'hbsag', 'nombre' => 'Antígeno de superficie de Hepatitis B (HBsAg)', 'unidad' => 'COI', 'referencia' => 'NO REACTIVO', 'area' => 'Bioquímica', 'orden' => 210],
        ['campo' => 'anti_hbs', 'nombre' => 'Anticuerpo contra el antígeno de superficie de Hepatitis B (Anti-HBs)', 'unidad' => 'mUI/mL', 'referencia' => '', 'area' => 'Bioquímica', 'orden' => 220],
        ['campo' => 'anti_hbc_total', 'nombre' => 'Anticuerpo contra el antígeno de la nucleocápside de Hepatitis B (Anti-HBc total)', 'unidad' => 'COI', 'referencia' => 'NO REACTIVO', 'area' => 'Bioquímica', 'orden' => 230],
        ['campo' => 'hcv', 'nombre' => 'Anticuerpo contra Hepatitis C', 'unidad' => 'COI', 'referencia' => 'NO REACTIVO', 'area' => 'Bioquímica', 'orden' => 240],
        ['campo' => 'htlv_1_2', 'nombre' => 'Anticuerpo para HTLV 1 y 2', 'unidad' => 'COI', 'referencia' => 'NO REACTIVO', 'area' => 'Bioquímica', 'orden' => 250],
    ],
];
