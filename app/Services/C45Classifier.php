<?php

namespace App\Services;

class C45Classifier
{
    protected array $tree = [];

    /**
     * Bangun pohon keputusan C4.5 dari dataset training.
     *
     * @param  array<int, array<string, mixed>>  $dataset  setiap baris berisi atribut kategorikal + key 'label'
     * @param  array<int, string>  $attributes  daftar nama atribut yang boleh dipakai untuk memecah node
     */
    public function buildTree(array $dataset, array $attributes): array
    {
        $this->tree = $this->buildNode($dataset, $attributes);

        return $this->tree;
    }

    /**
     * Klasifikasikan satu baris data dengan menelusuri pohon yang sudah dibangun.
     */
    public function classify(array $row, ?array $node = null): string
    {
        $node ??= $this->tree;

        if ($node['type'] === 'leaf') {
            return $node['label'];
        }

        $value = $row[$node['attribute']] ?? null;

        if ($value !== null && isset($node['branches'][$value])) {
            return $this->classify($row, $node['branches'][$value]);
        }

        // Nilai atribut tidak muncul di data training -> pakai kelas mayoritas node ini
        return $node['majority'];
    }

    /**
     * Entropy(S) = -sum(p_i * log2(p_i)) untuk setiap kelas i pada dataset.
     */
    public function calculateEntropy(array $dataset): float
    {
        $total = count($dataset);

        if ($total === 0) {
            return 0.0;
        }

        $counts = array_count_values(array_column($dataset, 'label'));

        $entropy = 0.0;
        foreach ($counts as $count) {
            $proportion = $count / $total;
            $entropy -= $proportion * log($proportion, 2);
        }

        return $entropy;
    }

    /**
     * Gain(S, A) = Entropy(S) - sum(|Sv|/|S| * Entropy(Sv)) untuk tiap nilai v dari atribut A.
     */
    public function calculateGain(array $dataset, string $attribute): float
    {
        $total = count($dataset);
        $baseEntropy = $this->calculateEntropy($dataset);

        $weightedEntropy = 0.0;
        foreach ($this->uniqueValues($dataset, $attribute) as $value) {
            $subset = $this->filterByAttribute($dataset, $attribute, $value);
            $weightedEntropy += (count($subset) / $total) * $this->calculateEntropy($subset);
        }

        return $baseEntropy - $weightedEntropy;
    }

    /**
     * SplitInfo(S, A) = -sum(|Sv|/|S| * log2(|Sv|/|S|)) — dipakai buat menormalkan Gain
     * supaya atribut dengan banyak nilai unik tidak otomatis "menang" (ciri khas C4.5 vs ID3).
     */
    public function calculateSplitInformation(array $dataset, string $attribute): float
    {
        $total = count($dataset);
        $splitInfo = 0.0;

        foreach ($this->uniqueValues($dataset, $attribute) as $value) {
            $subset = $this->filterByAttribute($dataset, $attribute, $value);
            $proportion = count($subset) / $total;

            if ($proportion > 0) {
                $splitInfo -= $proportion * log($proportion, 2);
            }
        }

        return $splitInfo;
    }

    /**
     * GainRatio(S, A) = Gain(S, A) / SplitInfo(S, A).
     */
    public function calculateGainRatio(array $dataset, string $attribute): float
    {
        $splitInfo = $this->calculateSplitInformation($dataset, $attribute);

        if ($splitInfo == 0.0) {
            return 0.0;
        }

        return $this->calculateGain($dataset, $attribute) / $splitInfo;
    }

    protected function buildNode(array $dataset, array $attributes): array
    {
        $labels = array_column($dataset, 'label');
        $majority = $this->majorityLabel($labels);

        if (count(array_unique($labels)) <= 1) {
            return ['type' => 'leaf', 'label' => $labels[0] ?? $majority, 'count' => count($dataset)];
        }

        if (empty($attributes)) {
            return ['type' => 'leaf', 'label' => $majority, 'count' => count($dataset)];
        }

        $gainRatios = [];
        foreach ($attributes as $attribute) {
            $gainRatios[$attribute] = $this->calculateGainRatio($dataset, $attribute);
        }
        arsort($gainRatios);
        $bestAttribute = array_key_first($gainRatios);

        if ($gainRatios[$bestAttribute] <= 0) {
            return ['type' => 'leaf', 'label' => $majority, 'count' => count($dataset)];
        }

        $remainingAttributes = array_values(array_diff($attributes, [$bestAttribute]));

        $branches = [];
        foreach ($this->uniqueValues($dataset, $bestAttribute) as $value) {
            $subset = $this->filterByAttribute($dataset, $bestAttribute, $value);

            $branches[$value] = empty($subset)
                ? ['type' => 'leaf', 'label' => $majority, 'count' => 0]
                : $this->buildNode($subset, $remainingAttributes);
        }

        return [
            'type' => 'node',
            'attribute' => $bestAttribute,
            'entropy' => round($this->calculateEntropy($dataset), 4),
            'gain_ratio' => round($gainRatios[$bestAttribute], 4),
            'majority' => $majority,
            'branches' => $branches,
        ];
    }

    protected function filterByAttribute(array $dataset, string $attribute, string $value): array
    {
        return array_values(array_filter($dataset, fn (array $row) => $row[$attribute] === $value));
    }

    protected function uniqueValues(array $dataset, string $attribute): array
    {
        return array_values(array_unique(array_column($dataset, $attribute)));
    }

    protected function majorityLabel(array $labels): ?string
    {
        if (empty($labels)) {
            return null;
        }

        $counts = array_count_values($labels);
        arsort($counts);

        return array_key_first($counts);
    }
}
