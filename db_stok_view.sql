create view v_01beli_laporan as
SELECT t_04beli.beli_id AS beli_id,
  t_04beli.tgl_beli AS tgl_beli,
  t_04beli.tgl_kirim AS tgl_kirim,
  t_01vendor.vendor_nama AS vendor_nama,
  t_02item.item_nama AS item_nama,
  t_04beli.qty AS qty,
  t_03satuan.satuan_nama AS satuan_nama,
  t_04beli.harga AS harga,
  t_04beli.sub_total AS sub_total,
  t_04beli.tgl_dp AS tgl_dp,
  t_04beli.jml_dp AS jml_dp,
  t_04beli.tgl_lunas AS tgl_lunas,
  t_04beli.jml_lunas AS jml_lunas
FROM ((t_04beli
  JOIN t_01vendor ON t_04beli.vendor_id = t_01vendor.vendor_id)
  JOIN t_02item ON t_04beli.item_id = t_02item.item_id)
  JOIN t_03satuan ON t_04beli.satuan_id = t_03satuan.satuan_id;
  
create view v_02jual_laporan as
SELECT t_06jual.no_po AS no_po,
  t_06jual.tgl AS tgl,
  t_05customer.customer_nama AS customer_nama,
  t_06jual.total AS total,
  t_07jual_detail.tgl_kirim AS tgl_kirim,
  t_02item.item_nama AS item_nama,
  t_07jual_detail.qty AS qty,
  t_03satuan.satuan_nama AS satuan_nama,
  t_07jual_detail.harga AS harga,
  t_07jual_detail.sub_total AS sub_total
FROM (((t_06jual
  JOIN t_07jual_detail ON t_06jual.jual_id = t_07jual_detail.jual_id)
  JOIN t_05customer ON t_06jual.customer_id = t_05customer.customer_id)
  JOIN t_02item ON t_07jual_detail.item_id = t_02item.item_id)
  JOIN t_03satuan ON t_07jual_detail.satuan_id = t_03satuan.satuan_id;
  
create view v_03transaksi as
SELECT a.item_id AS item_id,
  a.item_nama AS item_nama,
  b.tgl_beli AS tgl,
  y.vendor_nama AS vendor_customer,
  b.qty AS qty,
  'M' AS jenis,
  z.satuan_nama AS satuan_nama,
  b.harga AS harga,
  b.sub_total AS sub_total
FROM ((t_02item a
  LEFT JOIN t_04beli b ON a.item_id = b.item_id)
  LEFT JOIN t_01vendor y ON b.vendor_id = y.vendor_id)
  LEFT JOIN t_03satuan z ON b.satuan_id = z.satuan_id
UNION ALL
SELECT a.item_id AS item_id,
  a.item_nama AS item_nama,
  b.tgl_kirim AS tgl_kirim,
  y.customer_nama AS customer_nama,
  (b.qty * -(1)) AS qty,
  'K' AS K,
  z.satuan_nama AS satuan_nama,
  b.harga AS harga,
  ((b.qty * -(1)) * b.harga) AS `(``b``.``qty`` * -(1)) * ``b``.``harga```
FROM (((t_02item a
  LEFT JOIN t_07jual_detail b ON a.item_id = b.item_id)
  LEFT JOIN t_06jual c ON b.jual_id = c.jual_id)
  LEFT JOIN t_05customer y ON c.customer_id = y.customer_id)
  LEFT JOIN t_03satuan z ON b.satuan_id = z.satuan_id
ORDER BY item_id;
