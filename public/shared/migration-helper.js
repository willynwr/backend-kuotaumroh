#!/usr/bin/env node

/**
 * Migration Helper Script
 * Script ini membantu mengidentifikasi file yang masih menggunakan hardcoded URL
 * 
 * Cara menggunakan:
 * 1. Buka terminal/command prompt
 * 2. Jalankan: node migration-helper.js
 */

const fs = require('fs');
const path = require('path');

// Konfigurasi
const PROJECT_ROOT = path.join(__dirname, '..');
const HARDCODED_PATTERNS = [
    '',
    'http://127.0.0.1:8000/storage/',
    "'",
    '"',
    '`',
    "'http://127.0.0.1:8000/storage/",
    '"http://127.0.0.1:8000/storage/',
    '`http://127.0.0.1:8000/storage/',
];

const FILE_EXTENSIONS = ['.html', '.js'];
const EXCLUDE_DIRS = ['node_modules', '.git', 'public', 'docs'];

// Fungsi untuk scan file
function scanDirectory(dir, results = []) {
    const files = fs.readdirSync(dir);

    files.forEach(file => {
        const filePath = path.join(dir, file);
        const stat = fs.statSync(filePath);

        if (stat.isDirectory()) {
            if (!EXCLUDE_DIRS.includes(file)) {
                scanDirectory(filePath, results);
            }
        } else {
            const ext = path.extname(file);
            if (FILE_EXTENSIONS.includes(ext)) {
                results.push(filePath);
            }
        }
    });

    return results;
}

// Fungsi untuk check hardcoded URL
function checkHardcodedUrls(filePath) {
    const content = fs.readFileSync(filePath, 'utf8');
    const findings = [];

    HARDCODED_PATTERNS.forEach(pattern => {
        if (content.includes(pattern)) {
            // Count occurrences
            const count = (content.match(new RegExp(pattern.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g')) || []).length;
            findings.push({ pattern, count });
        }
    });

    return findings;
}

// Fungsi untuk check apakah sudah load config.js
function hasConfigLoaded(filePath) {
    if (!filePath.endsWith('.html')) return null;

    const content = fs.readFileSync(filePath, 'utf8');
    return content.includes('config.js');
}

// Main function
function main() {
    console.log('🔍 Scanning for hardcoded URLs...\n');

    const files = scanDirectory(PROJECT_ROOT);
    const results = [];

    files.forEach(filePath => {
        const findings = checkHardcodedUrls(filePath);
        if (findings.length > 0) {
            const relativePath = path.relative(PROJECT_ROOT, filePath);
            const hasConfig = hasConfigLoaded(filePath);

            results.push({
                path: relativePath,
                findings,
                hasConfig,
                totalCount: findings.reduce((sum, f) => sum + f.count, 0)
            });
        }
    });

    // Sort by total count (descending)
    results.sort((a, b) => b.totalCount - a.totalCount);

    // Print results
    console.log('📊 HASIL SCAN:\n');
    console.log(`Total file yang perlu diupdate: ${results.length}\n`);

    results.forEach((result, index) => {
        console.log(`${index + 1}. ${result.path}`);
        console.log(`   Total hardcoded URLs: ${result.totalCount}`);
        console.log(`   Config.js loaded: ${result.hasConfig === null ? 'N/A (not HTML)' : (result.hasConfig ? '✅ Yes' : '❌ No')}`);

        result.findings.forEach(finding => {
            console.log(`   - ${finding.pattern} (${finding.count}x)`);
        });

        console.log('');
    });

    // Summary
    console.log('\n📋 SUMMARY:');
    console.log(`Total files scanned: ${files.length}`);
    console.log(`Files with hardcoded URLs: ${results.length}`);
    console.log(`Files already using config.js: ${results.filter(r => r.hasConfig).length}`);
    console.log(`Files need config.js: ${results.filter(r => r.hasConfig === false).length}`);

    // Priority files
    const highPriority = results.filter(r => r.totalCount >= 5);
    if (highPriority.length > 0) {
        console.log('\n⚠️  HIGH PRIORITY (5+ hardcoded URLs):');
        highPriority.forEach(r => {
            console.log(`   - ${r.path} (${r.totalCount} URLs)`);
        });
    }

    console.log('\n✅ Scan complete!');
    console.log('\n💡 Next steps:');
    console.log('   1. Update files yang belum load config.js');
    console.log('   2. Replace hardcoded URLs dengan apiUrl() / storageUrl()');
    console.log('   3. Test semua fungsi');
    console.log('\n📚 Lihat CONFIG_README.md untuk panduan lengkap');
}

// Run
try {
    main();
} catch (error) {
    console.error('❌ Error:', error.message);
    process.exit(1);
}
