import fs from "node:fs";
import path from "node:path";
import { spawnSync } from "node:child_process";

const root = process.cwd();
const inventoryPath = path.join(root, "project-data", "inventory.json");

if (!fs.existsSync(inventoryPath)) {
  console.error("inventory.json not found");
  process.exit(1);
}

const inventory = JSON.parse(fs.readFileSync(inventoryPath, "utf8"));
const resources = inventory.resources ?? [];
const paths = inventory.paths ?? [];

const pathSlugs = new Set(paths.map((item) => item.slug));
const resourceSlugs = new Set(resources.map((item) => item.slug));

const errors = [];
const warnings = [];

for (const pathItem of paths) {
  for (const resourceSlug of pathItem.resourceSlugs ?? []) {
    if (!resourceSlugs.has(resourceSlug)) {
      errors.push(
        `Path "${pathItem.slug}" references missing resource slug "${resourceSlug}".`
      );
    }
  }
}

for (const resource of resources) {
  const filePath = path.join(root, resource.file);

  if (!fs.existsSync(filePath)) {
    errors.push(`Resource "${resource.slug}" points to missing file "${resource.file}".`);
  }

  for (const relatedPath of resource.relatedPaths ?? []) {
    if (!pathSlugs.has(relatedPath)) {
      errors.push(
        `Resource "${resource.slug}" references missing path slug "${relatedPath}".`
      );
    }
  }

  if (!Array.isArray(resource.domains) || resource.domains.length === 0) {
    warnings.push(`Resource "${resource.slug}" has no domains.`);
  }

  if (fs.existsSync(filePath)) {
    const pdfInfo = spawnSync("pdfinfo", [filePath], { encoding: "utf8" });

    if (pdfInfo.status === 0) {
      const match = pdfInfo.stdout.match(/^Pages:\s+(\d+)/m);
      if (match) {
        const actualPages = Number.parseInt(match[1], 10);
        if (actualPages !== resource.pages) {
          warnings.push(
            `Resource "${resource.slug}" page count mismatch: inventory=${resource.pages}, actual=${actualPages}.`
          );
        }
      }
    } else {
      warnings.push(`pdfinfo could not inspect "${resource.file}".`);
    }
  }
}

const duplicateFiles = new Map();

for (const resource of resources) {
  const basename = path.basename(resource.file);
  if (!duplicateFiles.has(basename)) {
    duplicateFiles.set(basename, []);
  }
  duplicateFiles.get(basename).push(resource.slug);
}

for (const [basename, slugs] of duplicateFiles.entries()) {
  if (slugs.length > 1) {
    warnings.push(`Multiple inventory entries share the same file name "${basename}": ${slugs.join(", ")}.`);
  }
}

const summary = {
  paths: paths.length,
  resources: resources.length,
  errors: errors.length,
  warnings: warnings.length,
};

console.log(JSON.stringify(summary, null, 2));

if (warnings.length) {
  console.log("\nWarnings:");
  for (const warning of warnings) {
    console.log(`- ${warning}`);
  }
}

if (errors.length) {
  console.log("\nErrors:");
  for (const error of errors) {
    console.log(`- ${error}`);
  }
  process.exit(1);
}
