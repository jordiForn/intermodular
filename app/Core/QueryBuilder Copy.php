package core;

import java.lang.reflect.Constructor;
import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

import app.database.DB;
import app.model.Model;

public class QueryBuilder<T extends Model<T>> {

    private String whereClause = "";
    private String orderByClause = "";
    private int limit = 0;
    private int offset = 0;
    private List<Object> parameters = new ArrayList<>();
    private boolean whereCalled = false;
    private boolean orderByCalled = false;
    private boolean limitCalled = false;

    public QueryBuilder where(String column, Object value) {
        if (orderByCalled || limitCalled) {
            throw new IllegalStateException("La cláusula WHERE debe ir antes de las cláusulas ORDER BY y LIMIT.");
        }
        if (whereClause.isEmpty()) {
            whereClause = column + " = ?";
        } else {
            whereClause += " AND " + column + " = ?";
        }
        parameters.add(value);
        whereCalled = true;
        return this;
    }

    public QueryBuilder orderBy(String column, String direction) {
        if (limitCalled) {
            throw new IllegalStateException("La cláusula ORDER BY debe ir antes de la cláusula LIMIT.");
        }
        if (orderByCalled) {
            throw new IllegalStateException("Solo puede haber una cláusula ORDER BY por consulta");
        }
        this.orderByClause = " ORDER BY " + column + " " + direction;
        orderByCalled = true;
        return this;
    }

    public QueryBuilder limit(int limit) {
        if (limitCalled) {
            throw new IllegalStateException("Solo puede haber una cláusula LIMIT por consulta.");
        }
        this.limit = limit;
        limitCalled = true;
        return this;
    }
    
    public QueryBuilder offset(int offset) {
        if (!limitCalled) {
            throw new IllegalStateException("La cláusula OFFSET debe ir después de una cláusula LIMIT.");
        }
        this.offset = offset;
        return this;
    }
    
	public List<T> get() throws SQLException, NoSuchMethodException, SecurityException, InstantiationException, IllegalAccessException, IllegalArgumentException, InvocationTargetException {
		String sql = "SELECT * FROM peliculas";
		if (!whereClause.isEmpty())
			sql += whereClause;
		if (!orderByClause.isEmpty())
			sql += orderByClause;
		if (limit > 0)
			sql += " LIMIT " + limit;

		try (Connection con = DB.connection(); PreparedStatement pstmt = con.prepareStatement(sql);) {
			ResultSet rs = pstmt.executeQuery();
			return mapAll(rs);
		}
	}

    public String buildQuery() {
        String query = "SELECT * FROM tabla";
        if (!whereClause.isEmpty()) {
            query += " WHERE " + whereClause;
        }
        if (!orderByClause.isEmpty()) {
            query += orderByClause;
        }
        if (limit > 0) {
            query += " LIMIT " + limit;
        }
        return query;
    }

    public List<Object> getParameters() {
        return parameters;
    }

    private List<T> mapAll(ResultSet rs) throws SQLException, NoSuchMethodException, SecurityException, InstantiationException, IllegalAccessException, IllegalArgumentException, InvocationTargetException {
		List<T> models = new ArrayList<>();
		while (rs.next()) {
			models.add(mapRow(rs));
		}
		return models;
	}
    
	protected T mapRow(ResultSet rs) throws SQLException, NoSuchMethodException, SecurityException, InstantiationException, IllegalAccessException, IllegalArgumentException, InvocationTargetException {
		
		Field[] fields = this.getClass().getDeclaredFields();
		
		Class cls = this.getClass();
		Class partypes[] = new Class[fields.length+1];

        partypes[0] = Integer.TYPE;
        
        int i=1;
        for(Field field : fields) {
        	
        	partypes[i++] = field.getType();
        	System.out.println(field.getName() + " - " + field.getType());
        }

        
        Constructor ct = cls.getConstructor(partypes);
         
         Object arglist[] = new Object[fields.length+1];
         arglist[0] = rs.getObject("id");
         i=1;
         for(Field field : fields) {
         	arglist[i++] = rs.getObject(field.getName());
         }

         return (T) ct.newInstance(arglist);
         
	}
}
