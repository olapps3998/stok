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
  
create view v_03masuk as
Select t_04beli.item_id As item_id,
  Sum(t_04beli.qty) As masuk
From t_04beli
Group By t_04beli.item_id;

create view v_04keluar as
Select t_07jual_detail.item_id As item_id,
  Sum(t_07jual_detail.qty) As keluar
From t_07jual_detail
Group By t_07jual_detail.item_id;

create view v_05stok as
Select a.item_id As item_id,
  a.item_nama As item_nama,
  (Case When isnull(b.masuk) Then 0 Else b.masuk End) As masuk,
  (Case When isnull(c.keluar) Then 0 Else c.keluar End) As keluar,
  ((Case When isnull(b.masuk) Then 0 Else b.masuk End) - (Case
    When isnull(c.keluar) Then 0 Else c.keluar End)) As saldo
From (t_02item a
  Left Join v_03masuk b On a.item_id = b.item_id)
  Left Join v_04keluar c On a.item_id = c.item_id
Order By item_id;

create view v_06transaksi as
Select a.item_id As item_id,
  a.item_nama As item_nama,
  b.tgl_beli As tgl,
  y.vendor_nama As vendor_customer,
  b.qty As qty,
  'M' As jenis,
  z.satuan_nama As satuan_nama,
  b.harga As harga,
  b.sub_total As sub_total
From ((t_02item a
  Left Join t_04beli b On a.item_id = b.item_id)
  Left Join t_01vendor y On b.vendor_id = y.vendor_id)
  Left Join t_03satuan z On b.satuan_id = z.satuan_id
union All
Select a.item_id As item_id,
  a.item_nama As item_nama,
  b.tgl_kirim As tgl_kirim,
  y.customer_nama As customer_nama,
  b.qty As qty,
  'K' As K,
  z.satuan_nama As satuan_nama,
  b.harga As harga,
  b.sub_total As sub_total
From (((t_02item a
  Left Join t_07jual_detail b On a.item_id = b.item_id)
  Left Join t_06jual c On b.jual_id = c.jual_id)
  Left Join t_05customer y On c.customer_id = y.customer_id)
  Left Join t_03satuan z On b.satuan_id = z.satuan_id
Order By item_id;

create view v_07mutasi as
Select v_06transaksi.item_id As item_id,
  v_06transaksi.item_nama As item_nama,
  v_06transaksi.tgl As tgl,
  (Case v_06transaksi.jenis When 'M' Then v_06transaksi.qty Else 0
  End) As masuk,
  (Case v_06transaksi.jenis When 'K' Then v_06transaksi.qty Else 0
  End) As keluar,
  v_06transaksi.qty As saldo
From v_06transaksi;

create view v_08harga_jual_terakhir as
Select Max(t_07jual_detail.tgl_kirim) As tgl_kirim_terakhir,
  t_07jual_detail.item_id As item_id,
  t_07jual_detail.harga As harga
From t_07jual_detail
Group By t_07jual_detail.item_id;

create view v_09margin as
Select b.vendor_nama As vendor_nama,
  c.item_nama As item_nama,
  d.satuan_nama As satuan_nama,
  a.harga As harga_beli,
  e.harga As harga_jual,
  (e.harga - a.harga) As margin_rp,
  (((e.harga - a.harga) / a.harga) * 100) As margin_prosen
From (((t_04beli a
  Left Join t_01vendor b On a.vendor_id = b.vendor_id)
  Left Join t_02item c On a.item_id = c.item_id)
  Left Join t_03satuan d On a.satuan_id = d.satuan_id)
  Left Join v_08harga_jual_terakhir e On a.item_id = e.item_id
Group By a.harga,
  a.item_id
Order By a.item_id,
  a.tgl_beli;

create view v_10hutang as  
Select b.vendor_nama As vendor_nama,
  Sum(a.sub_total) As tot_hutang,
  Sum(a.jml_dp) As tot_dp,
  Sum(a.jml_lunas) As tot_lunas,
  (Sum(a.sub_total) - (Sum(a.jml_dp) + Sum(a.jml_lunas))) As sisa
From t_04beli a
  Left Join t_01vendor b On a.vendor_id = b.vendor_id
Where ((Case When isnull(a.jml_dp) Then 0 Else a.jml_dp End) + (Case
    When isnull(a.jml_lunas) Then 0 Else a.jml_lunas End)) < a.sub_total
Group By a.vendor_id;