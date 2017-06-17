select d.dept_name as departamento,
       concat(e.first_name, ' ', e.last_name) as funcionario,
       x.dias
  from ( select *,
                datediff(coalesce(to_date, now()), from_date) as dias
           from dept_emp
       ) as x
       inner join employees e on e.emp_no = x.emp_no
       inner join departments d on d.dept_no = x.dept_no
 order by x.dias desc
 limit 10;
