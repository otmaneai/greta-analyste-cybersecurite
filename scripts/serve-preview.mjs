import http from "node:http";
import fs from "node:fs";
import path from "node:path";
import url from "node:url";

const __dirname = path.dirname(url.fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, "..");
const previewRoot = path.join(root, "preview");
const themeRoot = path.join(root, "wp-content", "themes", "greta-analyste-cybersecurite");
const port = Number.parseInt(process.env.PORT || "4321", 10);
const host = "127.0.0.1";

const contentTypes = {
  ".html": "text/html; charset=utf-8",
  ".css": "text/css; charset=utf-8",
  ".js": "application/javascript; charset=utf-8",
  ".png": "image/png",
  ".jpg": "image/jpeg",
  ".jpeg": "image/jpeg",
  ".webp": "image/webp",
  ".pdf": "application/pdf",
};

function resolvePath(requestPathname) {
  if (requestPathname === "/" || requestPathname === "") {
    return path.join(previewRoot, "index.html");
  }

  if (requestPathname.startsWith("/assets/")) {
    return path.join(previewRoot, requestPathname);
  }

  if (requestPathname.startsWith("/wp-content/themes/greta-analyste-cybersecurite/")) {
    const relative = requestPathname.replace("/wp-content/themes/greta-analyste-cybersecurite/", "");
    return path.join(themeRoot, relative);
  }

  if (requestPathname.startsWith("/pdfs_context/")) {
    return path.join(root, requestPathname);
  }

  return path.join(previewRoot, requestPathname);
}

const server = http.createServer((req, res) => {
  const pathname = decodeURIComponent(new URL(req.url, `http://${host}:${port}`).pathname);
  const resolved = resolvePath(pathname);

  if (!resolved.startsWith(root)) {
    res.writeHead(403);
    res.end("Forbidden");
    return;
  }

  fs.readFile(resolved, (error, content) => {
    if (error) {
      res.writeHead(404);
      res.end("Not found");
      return;
    }

    const ext = path.extname(resolved).toLowerCase();
    res.writeHead(200, {
      "Content-Type": contentTypes[ext] || "application/octet-stream",
    });
    res.end(content);
  });
});

server.on("error", (error) => {
  console.error(
    `Preview server failed on http://${host}:${port} (${error.code || "UNKNOWN"}). ` +
      "Set another port with PORT=xxxx npm run serve:preview."
  );
  process.exit(1);
});

server.listen(port, host, () => {
  console.log(`Preview server running at http://${host}:${port}`);
});
