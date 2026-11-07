<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class CashExportService
{
    /**
     * Generate file spreadsheet XLSX asli menggunakan ZipArchive PHP bawaan (tanpa dependensi composer tambahan).
     */
    public static function export(
        Organization $organization,
        Collection $transactions,
        float $totalIncome,
        float $totalExpense,
        float $currentBalance,
        ?string $monthLabel = null
    ): StreamedResponse {
        $tempFile = tempnam(sys_get_temp_dir(), 'kasly_xlsx_');
        $zip = new ZipArchive;
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // 3. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            .'</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 4. xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Buku Kas Mutasi" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // 5. xl/styles.xml (Palet Kasly: Navy #2F4862, Ice #DDEBFC, Font Outfit & Plus Jakarta Sans)
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<numFmts count="1">'
            .'<numFmt numFmtId="164" formatCode="&quot;Rp &quot;#,##0;[Red](&quot;Rp &quot;#,##0);&quot;Rp 0&quot;"/>'
            .'</numFmts>'
            .'<fonts count="5">'
            .'<font><sz val="10"/><name val="Segoe UI"/></font>' // 0: Normal
            .'<font><b/><sz val="14"/><color rgb="FF2F4862"/><name val="Segoe UI"/></font>' // 1: Title
            .'<font><sz val="10"/><color rgb="FF4A6583"/><name val="Segoe UI"/></font>' // 2: Subtitle
            .'<font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Segoe UI"/></font>' // 3: Table Header (White Bold)
            .'<font><b/><sz val="10"/><color rgb="FF2F4862"/><name val="Segoe UI"/></font>' // 4: Summary Bold
            .'</fonts>'
            .'<fills count="5">'
            .'<fill><patternFill patternType="none"/></fill>' // 0
            .'<fill><patternFill patternType="gray125"/></fill>' // 1
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF2F4862"/></patternFill></fill>' // 2: Navy 900
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFDDEBFC"/></patternFill></fill>' // 3: Ice 100
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFF8FAFC"/></patternFill></fill>' // 4: Ice Light
            .'</fills>'
            .'<borders count="2">'
            .'<border><left/><right/><top/><bottom/></border>' // 0: None
            .'<border>' // 1: Thin border
            .'<left style="thin"><color rgb="FFB5CCEB"/></left>'
            .'<right style="thin"><color rgb="FFB5CCEB"/></right>'
            .'<top style="thin"><color rgb="FFB5CCEB"/></top>'
            .'<bottom style="thin"><color rgb="FFB5CCEB"/></bottom>'
            .'</border>'
            .'</borders>'
            .'<cellXfs count="9">'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>' // 0: Normal
            .'<xf numFmtId="0" fontId="1" fillId="0" borderId="0"><alignment vertical="center"/></xf>' // 1: Title
            .'<xf numFmtId="0" fontId="2" fillId="0" borderId="0"><alignment vertical="center"/></xf>' // 2: Subtitle
            .'<xf numFmtId="0" fontId="3" fillId="2" borderId="1"><alignment horizontal="center" vertical="center"/></xf>' // 3: Table Header
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1"><alignment vertical="center"/></xf>' // 4: Data Text Normal
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1"><alignment horizontal="center" vertical="center"/></xf>' // 5: Data Text Center
            .'<xf numFmtId="164" fontId="0" fillId="0" borderId="1"><alignment horizontal="right" vertical="center"/></xf>' // 6: Data Currency
            .'<xf numFmtId="0" fontId="4" fillId="3" borderId="1"><alignment horizontal="right" vertical="center"/></xf>' // 7: Summary Label
            .'<xf numFmtId="164" fontId="4" fillId="3" borderId="1"><alignment horizontal="right" vertical="center"/></xf>' // 8: Summary Currency
            .'</cellXfs>'
            .'</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/worksheets/sheet1.xml (Data Transaksi Ormawa)
        $sheetXml = self::buildWorksheetXml(
            $organization,
            $transactions,
            $totalIncome,
            $totalExpense,
            $currentBalance,
            $monthLabel
        );
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

        $zip->close();

        $orgSlug = str_replace(' ', '-', preg_replace('/[^a-zA-Z0-9 ]/', '', $organization->name));
        $dateSlug = $monthLabel ? str_replace(' ', '-', $monthLabel) : date('Y-m');
        $fileName = "Buku-Kas-{$orgSlug}-{$dateSlug}.xlsx";

        return response()->streamDownload(function () use ($tempFile) {
            $stream = fopen($tempFile, 'rb');
            fpassthru($stream);
            fclose($stream);
            @unlink($tempFile);
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Susun baris dan kolom XML untuk worksheet buku kas.
     */
    private static function buildWorksheetXml(
        Organization $organization,
        Collection $transactions,
        float $totalIncome,
        float $totalExpense,
        float $currentBalance,
        ?string $monthLabel
    ): string {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';

        // Lebar Kolom
        $xml .= '<cols>';
        $xml .= '<col min="1" max="1" width="6" customWidth="1"/>'; // No
        $xml .= '<col min="2" max="2" width="14" customWidth="1"/>'; // Tanggal
        $xml .= '<col min="3" max="3" width="14" customWidth="1"/>'; // Tipe
        $xml .= '<col min="4" max="4" width="22" customWidth="1"/>'; // Kategori
        $xml .= '<col min="5" max="5" width="40" customWidth="1"/>'; // Keterangan / Keperluan
        $xml .= '<col min="6" max="6" width="16" customWidth="1"/>'; // Bukti Nota Fisik
        $xml .= '<col min="7" max="7" width="22" customWidth="1"/>'; // Pencatat / Verifikator
        $xml .= '<col min="8" max="8" width="20" customWidth="1"/>'; // Pemasukan (+)
        $xml .= '<col min="9" max="9" width="20" customWidth="1"/>'; // Pengeluaran (-)
        $xml .= '</cols>';

        $xml .= '<sheetData>';

        $rowIdx = 1;

        // Header Dokumen
        $titleText = htmlspecialchars("BUKU KAS MUTASI & TRANSPARANSI KEUANGAN - {$organization->name} ({$organization->code})", ENT_XML1);
        $xml .= "<row r=\"{$rowIdx}\" ht=\"26\" customHeight=\"1\">";
        $xml .= "<c r=\"A{$rowIdx}\" s=\"1\" t=\"inlineStr\"><is><t>{$titleText}</t></is></c>";
        $xml .= '</row>';
        $rowIdx++;

        $subTitle = htmlspecialchars('Periode Laporan: '.($monthLabel ?: 'Seluruh Riwayat Transaksi').' | Dicetak pada: '.date('d M Y H:i').' WIB melalui Kasly Platform', ENT_XML1);
        $xml .= "<row r=\"{$rowIdx}\" ht=\"20\" customHeight=\"1\">";
        $xml .= "<c r=\"A{$rowIdx}\" s=\"2\" t=\"inlineStr\"><is><t>{$subTitle}</t></is></c>";
        $xml .= '</row>';
        $rowIdx++;

        // Spacer Row
        $xml .= "<row r=\"{$rowIdx}\" ht=\"10\" customHeight=\"1\"/>";
        $rowIdx++;

        // Header Tabel Kolom
        $headerRow = $rowIdx;
        $xml .= "<row r=\"{$rowIdx}\" ht=\"24\" customHeight=\"1\">";
        $headers = [
            'A' => 'NO',
            'B' => 'TANGGAL',
            'C' => 'JENIS KAS',
            'D' => 'KATEGORI',
            'E' => 'KEPERLUAN / KETERANGAN',
            'F' => 'BUKTI NOTA',
            'G' => 'PENCATAT',
            'H' => 'PEMASUKAN (+)',
            'I' => 'PENGELUARAN (-)',
        ];
        foreach ($headers as $col => $title) {
            $xml .= "<c r=\"{$col}{$rowIdx}\" s=\"3\" t=\"inlineStr\"><is><t>{$title}</t></is></c>";
        }
        $xml .= '</row>';
        $rowIdx++;

        // Isi Data Transaksi
        $no = 1;
        foreach ($transactions as $tx) {
            $dateStr = $tx->transaction_date ? $tx->transaction_date->format('d/m/Y') : '-';
            $typeStr = $tx->type === 'in' ? 'Pemasukan' : 'Pengeluaran';
            $catStr = htmlspecialchars($tx->category->name ?? 'Kas Umum', ENT_XML1);
            $descStr = htmlspecialchars($tx->description ?? '-', ENT_XML1);
            $proofStr = $tx->proof_path ? 'Ada (Nota Fisik)' : 'Tanpa Nota';
            $creatorStr = htmlspecialchars($tx->creator->name ?? 'Sistem Kasly', ENT_XML1);

            $xml .= "<row r=\"{$rowIdx}\" ht=\"20\" customHeight=\"1\">";
            $xml .= "<c r=\"A{$rowIdx}\" s=\"5\" t=\"inlineStr\"><is><t>{$no}</t></is></c>";
            $xml .= "<c r=\"B{$rowIdx}\" s=\"5\" t=\"inlineStr\"><is><t>{$dateStr}</t></is></c>";
            $xml .= "<c r=\"C{$rowIdx}\" s=\"5\" t=\"inlineStr\"><is><t>{$typeStr}</t></is></c>";
            $xml .= "<c r=\"D{$rowIdx}\" s=\"4\" t=\"inlineStr\"><is><t>{$catStr}</t></is></c>";
            $xml .= "<c r=\"E{$rowIdx}\" s=\"4\" t=\"inlineStr\"><is><t>{$descStr}</t></is></c>";
            $xml .= "<c r=\"F{$rowIdx}\" s=\"5\" t=\"inlineStr\"><is><t>{$proofStr}</t></is></c>";
            $xml .= "<c r=\"G{$rowIdx}\" s=\"4\" t=\"inlineStr\"><is><t>{$creatorStr}</t></is></c>";

            if ($tx->type === 'in') {
                $xml .= "<c r=\"H{$rowIdx}\" s=\"6\"><v>{$tx->amount}</v></c>";
                $xml .= "<c r=\"I{$rowIdx}\" s=\"6\"><v>0</v></c>";
            } else {
                $xml .= "<c r=\"H{$rowIdx}\" s=\"6\"><v>0</v></c>";
                $xml .= "<c r=\"I{$rowIdx}\" s=\"6\"><v>{$tx->amount}</v></c>";
            }

            $xml .= '</row>';
            $rowIdx++;
            $no++;
        }

        // Baris Total Ringkasan Kas
        $xml .= "<row r=\"{$rowIdx}\" ht=\"22\" customHeight=\"1\">";
        $xml .= "<c r=\"A{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"B{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"C{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"D{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"E{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"F{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"G{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t>TOTAL MUTASI</t></is></c>";
        $xml .= "<c r=\"H{$rowIdx}\" s=\"8\"><v>{$totalIncome}</v></c>";
        $xml .= "<c r=\"I{$rowIdx}\" s=\"8\"><v>{$totalExpense}</v></c>";
        $xml .= '</row>';
        $rowIdx++;

        // Baris Saldo Kas Riil
        $xml .= "<row r=\"{$rowIdx}\" ht=\"24\" customHeight=\"1\">";
        $xml .= "<c r=\"A{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"B{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"C{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"D{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"E{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"F{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= "<c r=\"G{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t>SISA SALDO KAS AKTIF</t></is></c>";
        $xml .= "<c r=\"H{$rowIdx}\" s=\"8\"><v>{$currentBalance}</v></c>";
        $xml .= "<c r=\"I{$rowIdx}\" s=\"7\" t=\"inlineStr\"><is><t></t></is></c>";
        $xml .= '</row>';

        $xml .= '</sheetData>';
        $xml .= '</worksheet>';

        return $xml;
    }
}
