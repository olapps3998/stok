create view v_01beli_laporan as
Select t_04beli.beli_id As beli_id,
  t_04beli.tgl_beli As tgl_beli,
  t_04beli.tgl_kirim As tgl_kirim,
  t_01vendor.vendor_nama As vendor_nama,
  t_02item.item_nama As item_nama,
  t_04beli.qty As qty,
  t_03satuan.satuan_nama As satuan_nama,
  t_04beli.harga As harga,
  t_04beli.sub_total As sub_total,
  t_04beli.tgl_dp As tgl_dp,
  t_04beli.jml_dp As jml_dp,
  t_04beli.tgl_lunas As tgl_lunas,
  t_04beli.jml_lunas As jml_lunas
From ((t_04beli
  Join t_01vendor On t_04beli.vendor_id = t_01vendor.vendor_id)
  Join t_02item On t_04beli.item_id = t_02item.item_id)
  Join t_03satuan On t_04beli.satuan_id = t_03satuan.satuan_id;

create view v_02jual_laporan as  
Select t_06jual.no_po As no_po,
  t_06jual.tgl As tgl,
  t_05customer.customer_nama As customer_nama,
  t_06jual.total As total,
  t_07jual_detail.tgl_kirim As tgl_kirim,
  t_02item.item_nama As item_nama,
  t_07jual_detail.qty As qty,
  t_03satuan.satuan_nama As satuan_nama,
  t_07jual_detail.harga As harga,
  t_07jual_detail.sub_total As sub_total
From (((t_06jual
  Join t_07jual_detail On t_06jual.jual_id = t_07jual_detail.jual_id)
  Join t_05customer On t_06jual.customer_id = t_05customer.customer_id)
  Join t_02item On t_07jual_detail.item_id = t_02item.item_id)
  Join t_03satuan On t_07jual_detail.satuan_id = t_03satuan.satuan_id;
  
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
    SELECT 
        `a`.`item_id` AS `item_id`,
        `a`.`item_nama` AS `item_nama`,
        ((CASE
            WHEN ISNULL(`b`.`masuk`) THEN 0
            ELSE `b`.`masuk`
        END) + (CASE
            WHEN ISNULL(`d`.`qty`) THEN 0
            ELSE `d`.`qty`
        END)) AS `masuk`,
        (CASE
            WHEN ISNULL(`c`.`keluar`) THEN 0
            ELSE `c`.`keluar`
        END) + (CASE
            WHEN ISNULL(`e`.`qty`) THEN 0
            ELSE `e`.`qty`
        END) AS `keluar`,
        (((CASE
            WHEN ISNULL(`b`.`masuk`) THEN 0
            ELSE `b`.`masuk`
        END) + (CASE
            WHEN ISNULL(`d`.`qty`) THEN 0
            ELSE `d`.`qty`
        END)) - (CASE
            WHEN ISNULL(`c`.`keluar`) THEN 0
            ELSE `c`.`keluar`
        END) - (CASE
            WHEN ISNULL(`e`.`qty`) THEN 0
            ELSE `e`.`qty`
        END)) AS `saldo`
    FROM
        (((`t_02item` `a`
        LEFT JOIN `v_03masuk` `b` ON ((`a`.`item_id` = `b`.`item_id`)))
        LEFT JOIN `v_04keluar` `c` ON ((`a`.`item_id` = `c`.`item_id`)))
        LEFT JOIN `t_08item_saldo` `d` ON ((`a`.`item_id` = `d`.`item_id`)))
        left join t_11dead_stok e on a.item_id = e.item_id
    ORDER BY `a`.`item_id`;

create view v_13beli as
Select t_04beli.beli_id As beli_id,
  t_04beli.tgl_beli As tgl_beli,
  t_04beli.tgl_kirim As tgl_kirim,
  t_04beli.vendor_id As vendor_id,
  t_04beli.item_id As item_id,
  t_04beli.qty As qty,
  t_04beli.satuan_id As satuan_id,
  t_04beli.harga As harga,
  t_04beli.sub_total As sub_total,
  t_04beli.tgl_dp As tgl_dp,
  t_04beli.jml_dp As jml_dp,
  t_04beli.tgl_lunas As tgl_lunas,
  t_04beli.jml_lunas As jml_lunas
From t_04beli
Order By beli_id;

create view v_14jual_detail as
Select t_07jual_detail.jual_detail_id As jual_detail_id,
  t_07jual_detail.jual_id As jual_id,
  t_07jual_detail.tgl_kirim As tgl_kirim,
  t_07jual_detail.item_id As item_id,
  t_07jual_detail.qty As qty,
  t_07jual_detail.satuan_id As satuan_id,
  t_07jual_detail.harga As harga,
  t_07jual_detail.sub_total As sub_total
From t_07jual_detail
Order By jual_detail_id;

create view v_06transaksi as
    SELECT 
        `a`.`item_id` AS `item_id`,
        `a`.`item_nama` AS `item_nama`,
        `b`.`tgl_beli` AS `tgl`,
        `y`.`vendor_nama` AS `vendor_customer`,
        `b`.`qty` AS `qty`,
        'M' AS `jenis`,
        `z`.`satuan_nama` AS `satuan_nama`,
        `b`.`harga` AS `harga`,
        `b`.`sub_total` AS `sub_total`,
        `b`.`beli_id` AS `detail_id`
    FROM
        (((`t_02item` `a`
        LEFT JOIN `v_13beli` `b` ON ((`a`.`item_id` = `b`.`item_id`)))
        LEFT JOIN `t_01vendor` `y` ON ((`b`.`vendor_id` = `y`.`vendor_id`)))
        LEFT JOIN `t_03satuan` `z` ON ((`b`.`satuan_id` = `z`.`satuan_id`))) 
    UNION ALL SELECT 
        `a`.`item_id` AS `item_id`,
        `a`.`item_nama` AS `item_nama`,
        `b`.`tgl_kirim` AS `tgl_kirim`,
        `y`.`customer_nama` AS `customer_nama`,
        `b`.`qty` AS `qty`,
        'K' AS `K`,
        `z`.`satuan_nama` AS `satuan_nama`,
        `b`.`harga` AS `harga`,
        `b`.`sub_total` AS `sub_total`,
        `b`.`jual_detail_id` AS `jual_detail_id`
    FROM
        ((((`t_02item` `a`
        LEFT JOIN `v_14jual_detail` `b` ON ((`a`.`item_id` = `b`.`item_id`)))
        LEFT JOIN `t_06jual` `c` ON ((`b`.`jual_id` = `c`.`jual_id`)))
        LEFT JOIN `t_05customer` `y` ON ((`c`.`customer_id` = `y`.`customer_id`)))
        LEFT JOIN `t_03satuan` `z` ON ((`b`.`satuan_id` = `z`.`satuan_id`))) 
    UNION ALL SELECT 
        `a`.`item_id` AS `item_id`,
        `a`.`item_nama` AS `item_nama`,
        `b`.`tgl` AS `tgl`,
        'Dead-Stock' AS `Dead-Stock`,
        `b`.`qty` AS `qty`,
        'K' AS `K`,
        `c`.`satuan_nama` AS `satuan_nama`,
        0 AS `0`,
        0 AS `0`,
        `b`.`dead_stok_id` AS `dead_stok_id`
    FROM
        ((`t_02item` `a`
        LEFT JOIN `t_11dead_stok` `b` ON ((`a`.`item_id` = `b`.`item_id`)))
        LEFT JOIN `t_03satuan` `c` ON ((`b`.`satuan_id` = `c`.`satuan_id`)))
    ORDER BY `item_id` , `tgl` , `jenis` DESC , `detail_id`;

create view v_07mutasi as  
Select v_06transaksi.item_id As item_id,
  v_06transaksi.item_nama As item_nama,
  v_06transaksi.tgl As tgl,
  (Case v_06transaksi.jenis When 'M' Then v_06transaksi.qty Else 0
  End) As masuk,
  (Case v_06transaksi.jenis When 'K' Then v_06transaksi.qty Else 0
  End) As keluar,
  v_06transaksi.qty As saldo,
  v_06transaksi.jenis As jenis,
  v_06transaksi.detail_id As detail_id
From v_06transaksi
union All
Select a.item_id As item_id,
  b.item_nama As item_nama,
  a.tgl As tgl,
  a.qty As qty,
  0 As `0`,
  a.qty As qty,
  'M' As M,
  0 As `0`
From t_08item_saldo a
  Left Join t_02item b On a.item_id = b.item_id
Order By item_id,
  tgl,
  jenis Desc,
  detail_id;
  
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

create view v_11piutang as
Select a.no_po As no_po,
  a.tgl As tgl,
  b.customer_nama As customer_nama,
  a.total As tot_piutang,
  a.inv_no As inv_no,
  a.inv_tgl As inv_tgl,
  a.inv_jml As inv_jml,
  a.bayar_tgl As bayar_tgl,
  a.bayar_jml As tot_bayar,
  (a.total - (Case When isnull(a.bayar_jml) Then 0 Else a.bayar_jml
  End)) As sisa
From t_06jual a
  Left Join t_05customer b On a.customer_id = b.customer_id
Where (Case When isnull(a.bayar_jml) Then 0 Else a.bayar_jml End) < a.total;

create view v_12nilai_stok as
    SELECT 
        `a`.`item_id` AS `item_id`,
        `a`.`item_nama` AS `item_nama`,
        `b`.`beli_id` AS `detail_id`,
        `b`.`tgl_beli` AS `tgl`,
        `y`.`vendor_nama` AS `vendor_customer`,
        `b`.`qty` AS `qty`,
        'M' AS `jenis`,
        `z`.`satuan_nama` AS `satuan_nama`,
        `b`.`harga` AS `harga`,
        `b`.`sub_total` AS `sub_total`
    FROM
        (((`t_02item` `a`
        LEFT JOIN `v_13beli` `b` ON ((`a`.`item_id` = `b`.`item_id`)))
        LEFT JOIN `t_01vendor` `y` ON ((`b`.`vendor_id` = `y`.`vendor_id`)))
        LEFT JOIN `t_03satuan` `z` ON ((`b`.`satuan_id` = `z`.`satuan_id`))) 
    UNION ALL SELECT 
        `a`.`item_id` AS `item_id`,
        `a`.`item_nama` AS `item_nama`,
        `b`.`jual_detail_id` AS `jual_detail_id`,
        `b`.`tgl_kirim` AS `tgl_kirim`,
        `y`.`customer_nama` AS `customer_nama`,
        `b`.`qty` AS `qty`,
        'K' AS `K`,
        `z`.`satuan_nama` AS `satuan_nama`,
        `b`.`harga` AS `harga`,
        `b`.`sub_total` AS `sub_total`
    FROM
        ((((`t_02item` `a`
        LEFT JOIN `v_14jual_detail` `b` ON ((`a`.`item_id` = `b`.`item_id`)))
        LEFT JOIN `t_06jual` `c` ON ((`b`.`jual_id` = `c`.`jual_id`)))
        LEFT JOIN `t_05customer` `y` ON ((`c`.`customer_id` = `y`.`customer_id`)))
        LEFT JOIN `t_03satuan` `z` ON ((`b`.`satuan_id` = `z`.`satuan_id`))) 
    UNION ALL SELECT 
        `a`.`item_id` AS `item_id`,
        `a`.`item_nama` AS `item_nama`,
        `b`.`tgl` AS `tgl`,
        'Dead-Stock' AS `Dead-Stock`,
        `b`.`qty` AS `qty`,
        'K' AS `K`,
        `c`.`satuan_nama` AS `satuan_nama`,
        0 AS `0`,
        0 AS `0`,
        `b`.`dead_stok_id` AS `dead_stok_id`
    FROM
        ((`t_02item` `a`
        LEFT JOIN `t_11dead_stok` `b` ON ((`a`.`item_id` = `b`.`item_id`)))
        LEFT JOIN `t_03satuan` `c` ON ((`b`.`satuan_id` = `c`.`satuan_id`))) 
    UNION ALL SELECT 
        `a`.`item_id` AS `item_id`,
        `a`.`item_nama` AS `item_nama`,
        `b`.`dead_stok_id` AS `dead_stok_id`,
        `b`.`tgl` AS `tgl`,
        'Dead-Stock' AS `Dead-Stock`,
        `b`.`qty` AS `qty`,
        'K' AS `K`,
        `c`.`satuan_nama` AS `satuan_nama`,
        0 AS `0`,
        0 AS `0`
    FROM
        ((`t_02item` `a`
        LEFT JOIN `t_11dead_stok` `b` ON ((`a`.`item_id` = `b`.`item_id`)))
        LEFT JOIN `t_03satuan` `c` ON ((`b`.`satuan_id` = `c`.`satuan_id`)))
    ORDER BY `item_id` , `tgl` , `jenis` DESC , `detail_id`;

create view v_15penjualan as  
Select t_07jual_detail.item_id As item_id,
  Sum((t_07jual_detail.qty * t_07jual_detail.harga)) As penjualan
From t_07jual_detail
Group By t_07jual_detail.item_id;

create view v_16hpp as
Select a.item_id As item_id,
  a.item_nama As item_nama,
  b.sap As sap,
  Sum(a.out_sub_total) As hpp,
  c.penjualan As penjualan,
  (c.penjualan - Sum(a.out_sub_total)) As lr_kotor
From (t_09nilai_stok a
  Left Join t_10sap b On a.item_id = b.item_id)
  Left Join v_15penjualan c On a.item_id = c.item_id
Group By a.item_id;