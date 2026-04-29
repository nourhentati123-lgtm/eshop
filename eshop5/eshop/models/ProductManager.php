<?php
class ProductManager {

    public function __construct(private PDO $db) {}

    private function row(array $r): Product {
        $p           = new Product();
        $p->code     = $r['code']     ?? '';
        $p->name     = $r['name']     ?? '';
        $p->price    = (float)($r['price']    ?? 0);
        $p->category = $r['category'] ?? '';
        $p->stock    = (int)($r['stock']      ?? 0);
        $p->image    = $r['image']    ?? 'default.jpg';
        return $p;
    }

    public function getAll(): array {
        $rows = $this->db
            ->query("SELECT * FROM produits ORDER BY name ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'row'], $rows);
    }

    public function search(string $name): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM produits WHERE name LIKE ? ORDER BY name ASC"
        );
        $stmt->execute(["%$name%"]);
        return array_map([$this, 'row'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function find(string $code): Product|false {
        $stmt = $this->db->prepare("SELECT * FROM produits WHERE code = ?");
        $stmt->execute([$code]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r ? $this->row($r) : false;
    }

    public function codeExists(string $code): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM produits WHERE code = ?");
        $stmt->execute([$code]);
        return (bool) $stmt->fetchColumn();
    }

    public function insert(Product $p): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO produits (code, name, price, category, stock, image)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $p->code, $p->name, $p->price,
            $p->category, $p->stock, $p->image
        ]);
    }

    public function update(Product $p): bool {
        $stmt = $this->db->prepare(
            "UPDATE produits
             SET name=?, price=?, category=?, stock=?, image=?
             WHERE code=?"
        );
        return $stmt->execute([
            $p->name, $p->price, $p->category,
            $p->stock, $p->image, $p->code
        ]);
    }

    public function delete(string $code): bool {
        $stmt = $this->db->prepare("DELETE FROM produits WHERE code = ?");
        return $stmt->execute([$code]);
    }
}
