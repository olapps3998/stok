BEGIN

	declare vqty_out double(15,2);
	declare vidpart_warehouse int(11);
	declare vtgl_stock date;
	declare vidpart int(11);
	
	declare vitem_id int(11);
	declare vtgl date;
	declare vqty float(15,2);
	
	select 
		pst.idpart_warehouse,
		pst.idpart,
		date(pst.tgl),
		pst.qty_out 
	into 
		vidpart_warehouse,
		vidpart,
		vtgl_stock,
		vqty_out 
	from 
		part_stock pst
	where 
		idpart_stock = paridpart_stock;
		
	select
		item_id,
		date(tgl),
		qty
	into
		vitem_id,
		vtgl,
		vqty
	from
		v_12nilai_stok
	where
		item_id = paritem_id;
		
	if is_in = 1 then
		begin
			insert into part_stock_fifo (
				idpart_stock,
				idpart,
				idpart_warehouse,
				tgl,
				tgl_price,
				qty_in,
				price_buy)
			select 
				idpart_stock,
				idpart,
				idpart_warehouse,
				date(tgl),
				date(tgl),
				qty_in,
				price_buy
			from 
				part_stock 
			where 
				idpart_stock = paridpart_stock
				and qty_in > 0;
		end;
	else
		begin
			declare vidpart_fifo int(11);
			declare vidpart_warehouse_fifo int(11);
			declare vtgl_stock_fifo date;
			declare vidpart_stock BIGINT(20);
			declare vqty_sisa double(15,2);
			declare vprice_buy double(15,2);
			declare cpart_stock_fifo
			cursor for 
				select 
					psf.idpart_warehouse,
					psf.idpart,
					psf.tgl,
					sum(psf.qty_in - psf.qty_out) as qty_sisa,
					psf.price_buy
				from 
					part_stock_fifo psf
				where 
					psf.IDpart_warehouse = vidpart_warehouse
					and psf.IDpart = vidpart
					and date(psf.tgl) <= vtgl_stock
				group by 
					price_buy,
					idpart_warehouse,
					idpart
				having 
					qty_sisa > 0
				order by 
					tgl desc;

			declare exit handler for not found begin end;

			open cpart_stock_fifo;
			
			label1:loop

			fetch 
				cpart_stock_fifo 
			into 
				vidpart_warehouse_fifo,
				vidpart_fifo,
				vtgl_stock_fifo,
				vqty_sisa,
				vprice_buy;
			
			set vqty_out = vqty_out - vqty_sisa;

			if vqty_out < 0 then
				begin
					set vqty_out = vqty_out + vqty_sisa;
					insert into part_stock_fifo(
						idpart_stock,
						idpart_warehouse,
						idpart,
						tgl,
						tgl_price,
						qty_out,
						price_buy) values (
						paridpart_stock,
						vidpart_warehouse_fifo,
						vidpart_fifo,
						vtgl_stock,
						vtgl_stock_fifo,
						vqty_out,
						vprice_buy);

					leave label1;
				end;
			else
				insert into part_stock_fifo(
					idpart_stock,
					idpart_warehouse,
					idpart,
					tgl,
					tgl_price,
					qty_out,
					price_buy) values (
					paridpart_stock,
					vidpart_warehouse_fifo,
					vidpart_fifo,
					vtgl_stock,
					vtgl_stock_fifo,
					vqty_sisa,
					vprice_buy);
			end if;

			end loop label1;

		end;
	end if;

END