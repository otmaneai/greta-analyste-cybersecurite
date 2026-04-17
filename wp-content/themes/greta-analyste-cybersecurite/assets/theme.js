window.gacPathArchive = function (paths) {
  return {
    paths,
    level: "all",
    get levels() {
      return [...new Set(this.paths.map((path) => path.level))];
    },
    get filtered() {
      return this.paths.filter((path) => {
        if (this.level !== "all" && path.level !== this.level) {
          return false;
        }
        return true;
      });
    },
  };
};

window.gacResourceLibrary = function (resources) {
  return {
    resources,
    query: "",
    domain: "all",
    status: "all",
    format: "all",
    get domains() {
      return [...new Set(this.resources.flatMap((resource) => resource.domains))];
    },
    get statuses() {
      return [...new Set(this.resources.map((resource) => resource.status))];
    },
    get formats() {
      return [...new Set(this.resources.map((resource) => resource.format))];
    },
    get filtered() {
      const term = this.query.trim().toLowerCase();

      return this.resources.filter((resource) => {
        const matchesQuery =
          !term ||
          resource.title.toLowerCase().includes(term) ||
          resource.summary.toLowerCase().includes(term) ||
          resource.file.toLowerCase().includes(term) ||
          resource.domains.join(" ").toLowerCase().includes(term);

        const matchesDomain =
          this.domain === "all" || resource.domains.includes(this.domain);
        const matchesStatus =
          this.status === "all" || resource.status === this.status;
        const matchesFormat =
          this.format === "all" || resource.format === this.format;

        return matchesQuery && matchesDomain && matchesStatus && matchesFormat;
      });
    },
  };
};
